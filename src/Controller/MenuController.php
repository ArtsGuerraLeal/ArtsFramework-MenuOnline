<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


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
     * @Route("/create", name="menu_create")
     */
    public function create(): Response
    {
        return $this->render('menu/create.html.twig', [
            'controller_name' => 'MenuController',
        ]);
    }

    /**
     * @Route("/edit", name="menu_edit")
     */
    public function edit(): Response
    {
        return $this->render('menu/edit.html.twig', [
            'controller_name' => 'MenuController',
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
