<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use App\Repository\SaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/dashboard", name="home.")
 */
class HomeController extends AbstractController
{



    /**
     * @var MenuRepository
     */
    private $menuRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    private $security;

    /**
     * @var SaleRepository
     */
    private $saleRepository;


    public function __construct(SaleRepository $saleRepository, MenuRepository $menuRepository, EntityManagerInterface $entityManager,Security $security){
        $this->entityManager = $entityManager;
        $this->menuRepository = $menuRepository;
        $this->saleRepository = $saleRepository;

        $this->security = $security;

    }

    /**
     * @Route("/", name="index", methods={"GET"})
     * @param MenuRepository $MenuRepository
     * @return Response
     */
    public function index(MenuRepository $MenuRepository): Response
    {
        $user = $this->security->getUser();
       

        if($user->getRoles())

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'menus' => $MenuRepository->findByCompany($user->getCompany()),
            'visits' => $MenuRepository->SumByCompanyVisits($user->getCompany()),
            'calls' => $MenuRepository->SumByCompanyCalls($user->getCompany()),
            'whatsapp' => $MenuRepository->SumByCompanyWhatsapp($user->getCompany())


        ]);

    }

    /**
     * @Route("/sales", name="sales_dashboard", methods={"GET"})
     * @param SaleRepository $saleRepository
     * @return Response
     */
    public function SaleDashboard(SaleRepository $saleRepository): Response
    {
        $user = $this->security->getUser();

        return $this->render('home/SalesDashboard.html.twig', [
            'controller_name' => 'HomeController',
            'sales' => $saleRepository->findByCompany($user->getCompany()),
        ]);

    }

    /**
     * @Route("/test", name="architect", methods={"GET"})
     * @param AppointmentRepository $appointmentRepository
     * @return Response
     */
    public function architect(MenuRepository $MenuRepository): Response
    {
        $user = $this->security->getUser();

        return $this->render('base_architect.html.twig', [
            'controller_name' => 'HomeController',
            'menu' => $MenuRepository->findByCompany($user->getCompany()),
        ]);


    }
}
