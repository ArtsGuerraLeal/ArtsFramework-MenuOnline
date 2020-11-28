<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/", name="landing.")
 */
class LandingController extends AbstractController
{
    private $security;

    public function __construct(Security $security){

        $this->security = $security;

    }
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
     * @Route("/p/{name}", name="menu_view_name", methods={"GET"})
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
     * @Route("/p/{name}/payment", name="menu_payment", methods={"GET"})
     * @param MenuRepository $menuRepository
     * @param $name
     * @return Response
     */
    public function PayService(MenuRepository $menuRepository,$name): Response
    {
        $this->denyAccessUnlessGranted('ROLE_COMPANY_ADMIN');
        $menu = $menuRepository->findOneBy(['name'=>$name]);
        \Stripe\Stripe::setApiKey('sk_test_HHOQhx8Nk5r0LJGDUaxYlfRK004xJe9Yiv');
        $stripe = new \Stripe\StripeClient('sk_test_HHOQhx8Nk5r0LJGDUaxYlfRK004xJe9Yiv');



        return $this->render('menu/payment.html.twig', [
            'menu_data' => $menu,
        ]);
    }
}
