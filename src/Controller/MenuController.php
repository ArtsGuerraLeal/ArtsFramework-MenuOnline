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
    private $menuRepository;


    public function __construct( EntityManagerInterface $entityManager, Security $security, MenuRepository $menuRepository){
        $this->entityManager = $entityManager;
        $this->menuRepository = $menuRepository;
        $this->security = $security;

    }

    /**
     * @Route("/", name="menu_index", methods={"GET"})
     * @param MenuRepository $menuRepository
     * @return Response
     */
    public function index( MenuRepository $menuRepository): Response
    {
        $user = $this->security->getUser();
        return $this->render('menu/index.html.twig', [
            'menus' => $menuRepository->findByCompany($user->getCompany()),
        ]);
    }

    /**
     * @Route("/create", name="menu_create", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function Create(Request $request): Response
    {
        $menu = new Menu();
        $config = parse_ini_file('../AmazonConfig.ini');
        $skey = $config['amazon_secret_key'];
        $key = $config['amazon_key'];

        $s3 = new S3Client([
            'region'  => 'us-east-2',
            'version' => 'latest',
            'credentials' => [
                'key'    => $key,
                'secret' => $skey,
            ]
        ]);

        $user = $this->security->getUser();
        $menu->setCompany($user->getCompany());


        $form = $this->createForm(MenuType::class, $menu);
        $menu->setWebsiteVisits(0);
        $menu->setVisits(0);
        $menu->setPromotionVisits(0);
        $menu->setWhatsappVisits(0);
        $menu->setPhoneVisits(0);
        $menu->setInfoVisits(0);
        $menu->setNumOfImages(1);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            /**@var UploadedFile $file */
            $file = $request->files->get('menu')['attachment'];
            if($file){
                $imageID = md5(uniqid());

                $filename = $imageID . '_1' . $file->guessClientExtension();

                $temp_file_location = $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename);

                $menu->setImage($imageID);
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
    public function edit(Request $request, MenuRepository $menuRepository,$id): Response
    {
        $menu = $menuRepository->findOneBy(['id'=>$id]);
        $user = $this->security->getUser();
        $company = $user->getCompany();
        $entityManager = $this->getDoctrine()->getManager();
        $menu = $menuRepository->findOneByCompanyID($user->getCompany(), $id);

        $form = $this->createForm(MenuType::class, $menu);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /**@var UploadedFile $file */
            $file = $request->files->get('menu')['attachment'];
            if($file){
                $imageID = md5(uniqid());

                $filename = $imageID . '_1' . $file->guessClientExtension();

                $temp_file_location = $file->move(
                    $this->getParameter('uploads_dir'),
                    $filename);

                $menu->setImage($imageID);
                $s3->putObject([
                    'Bucket' => 'fastmarkets',
                    'Key'    => $filename,
                    'SourceFile' => $temp_file_location,
                    'ACL'    => 'public-read'
                ]);

                unlink($temp_file_location);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('menu_index');
        }

        return $this->render('menu/edit_data.html.twig', [
            'menu_data' => $menu,
            'form' => $form->createView(),

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
     * @Route("/{name}", name="menu_view_name", methods={"GET"})
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

    /**
     * @Route("/visit", name="visit_counter", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function Visit(Request $request):JsonResponse
    {
        if ($request->getMethod() == 'POST')
        {
            $visitData = $request->request->get('visitData');
            $name = $request->request->get('name');

        }
        else {
            die();
        }
        $em = $this->getDoctrine()->getManager();

        $menu = $this->menuRepository->findOneBy(['name'=>$name]);

        if($visitData == 'visit'){
            $visits = $menu->getVisits()+1;
            $menu->setVisits($visits);
        }elseif ($visitData == 'phone'){
            $visits = $menu->getPhoneVisits()+1;
            $menu->setPhoneVisits($visits);
        }elseif ($visitData == 'whatsapp'){
            $visits = $menu->getWhatsappVisits()+1;
            $menu->setWhatsappVisits($visits);
        }elseif ($visitData == 'website'){
            $visits = $menu->getWebsiteVisits()+1;
            $menu->setWebsiteVisits($visits);
        }elseif ($visitData == 'info'){
            $visits = $menu->getInfoVisits()+1;
            $menu->setInfoVisits($visits);
        }elseif ($visitData == 'promo'){
            $visits = $menu->getPromotionVisits()+1;
            $menu->setPromotionVisits($visits);
        }

        $em->persist($menu);
        $em->flush();

        $returnResponse = new JsonResponse();
        $returnResponse->setjson("Json recieved");

        return $returnResponse;

    }
}
