<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use App\Repository\MenuRepository;
use Aws\AwsClient;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Aws\S3\S3Client;

/**
 * @Route("/menu")
 */
class MenuController extends AbstractController
{
    private $security;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;


    public function __construct( EntityManagerInterface $entityManager, Security $security){
        $this->entityManager = $entityManager;

        $this->security = $security;

    }

    /**
     * @Route("/create", name="menu_create", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function Create(Request $request): Response
    {
        $menu = new Menu();


        $s3 = new S3Client([
            'region'  => 'us-east-2',
            'version' => 'latest',
            'credentials' => [
                'key'    => "AKIAIZNSU6EHVZKQ45ZQ",
                'secret' => "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",
            ]
        ]);



        $user = $this->security->getUser();
        $menu->setCompany($user->getCompany());
        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            /**@var UploadedFile $file */
            $file = $request->files->get('menu')['attachment'];
            if($file){
                $filename = md5(uniqid()). '.' . $file->guessClientExtension();
                $temp_file_location = $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename);

                $menu->setImage($filename);
                $s3->putObject([
                    'Bucket' => 'fastmarkets',
                    'Key'    => $filename,
                    'SourceFile' => $temp_file_location,
                    'ACL'    => 'public-read'
                ]);

                unlink($temp_file_location);
            }
            $entityManager->persist($menu);
            $entityManager->flush();
            return $this->redirectToRoute('home.index');
        }

        return $this->render('menu/create.html.twig', [
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/{id}/edit", name="menu_edit")
     * @param MenuRepository $menuRepository
     * @param $id
     * @return Response
     */
    public function edit(MenuRepository $menuRepository,$id): Response
    {
        $menu = $menuRepository->findOneBy(['id'=>$id]);


        return $this->render('menu/edit.html.twig', [
            'menu_data' => $menu,
        ]);
    }

    /**
     * @Route("/{id}/view", name="menu_view", methods={"GET"})
     * @param MenuRepository $menuRepository
     * @param $id
     * @return Response
     */
    public function view(MenuRepository $menuRepository,$id): Response
    {
        $menu = $menuRepository->findOneBy(['id'=>$id]);

        return $this->render('menu/view.html.twig', [
            'menu_data' => $menu,
        ]);
    }

    /**
     * @Route("/{name}", name="menu_vie_name", methods={"GET"})
     * @param MenuRepository $menuRepository
     * @param $name
     * @return Response
     */
    public function ViewName(MenuRepository $menuRepository,$name): Response
    {
        $menu = $menuRepository->findOneBy(['name'=>$name]);

        return $this->render('menu/view.html.twig', [
            'menu_data' => $menu,
        ]);
    }

    /**
     * @Route("/save", name="save_menu", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request):JsonResponse
    {
        if ($request->getMethod() == 'POST')
        {
            $menuData = $request->request->get('menuData');
        }
        else {
            die();
        }



        $em = $this->getDoctrine()->getManager();
        $menu = new Menu();
        $user = $this->security->getUser();
        $menu->setCompany($user->getCompany());



        $menu->setData($menuData);
        $menu->setImage("pending.jpg");

        $em->persist($menu);
        $em->flush();

        $returnResponse = new JsonResponse();
        $returnResponse->setjson("Json recieved");

        return $returnResponse;


    }
}
