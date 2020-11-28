<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/", name="landing.")
 */
class LandingController extends AbstractController
{
    /**
     * @Route("/", name="page")
     */
    public function index()
    {
        return $this->render('landing/index.html.twig', [
            'controller_name' => 'LandingController',
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
}
