<?php

namespace App\Controller;

use App\Repository\MenuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/privacy", name="privacy")
     */
    public function privacy()
    {
        return $this->render('landing/privacy.html.twig', [
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
    //    $this->denyAccessUnlessGranted('ROLE_COMPANY_ADMIN');
        $menu = $menuRepository->findOneBy(['name'=>$name]);

        return $this->render('menu/payment.html.twig', [
            'menu_data' => $menu,
        ]);
    }

    /**
     * @Route("/testwebhook", name="test_webhook", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function webhook(Request $request): JsonResponse
    {
        return new JsonResponse(['Response' => 1]);

    }

    /**
     * @Route("/success", name="menu_payment_success", methods={"GET"})
     * @param MenuRepository $menuRepository
     * @return Response
     */
    public function success(MenuRepository $menuRepository): Response
    {
    //    $this->denyAccessUnlessGranted('ROLE_COMPANY_ADMIN');

        return $this->render('menu/success.html.twig', [

        ]);
    }

    /**
     * @Route("/canceled", name="menu_payment_canceled", methods={"GET"})
     * @param MenuRepository $menuRepository
     * @return Response
     */
    public function canceled(MenuRepository $menuRepository): Response
    {
    //    $this->denyAccessUnlessGranted('ROLE_COMPANY_ADMIN');

        return $this->render('menu/canceled.html.twig', [

        ]);
    }

    /**
     * @Route("/config", name="fetch_stripe_config", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchStripeConfig(Request $request): JsonResponse
    {
    //    $this->denyAccessUnlessGranted('ROLE_COMPANY_ADMIN');

        $config = parse_ini_file('../StripeConfig.ini');

        $key = $config['stripe_publishable_key'];

        $response = new JsonResponse([
            'publishableKey' => $key,
            'basicPrice' => $config['basic_price_id'],
            'proPrice' => $config['pro_price_id']
        ]);

        return $response;

    }

    /**
     * @Route("/create-checkout-session", name="fetch_stripe_checkout", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function createCheckoutSession(Request $request): JsonResponse
    {
        $config = parse_ini_file('../StripeConfig.ini');

        $key = $config['stripe_secret_key'];

        Stripe::setApiKey($key);

        $stripe = new \Stripe\StripeClient(
            $key
        );

        if ($request->getMethod() == 'POST')
        {
            $priceId =  $request->request->get('priceId');

        }
        else {
            die();
        }

        $price = $stripe->prices->retrieve(
            $priceId,
            []
        );


        $domain_url = 'https://fastmarkets.com';

        $checkout_session = \Stripe\Checkout\Session::create([
            'success_url' => $domain_url . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $domain_url . '/canceled',
            'payment_method_types' => ['card'],
            'mode' => 'subscription',
            'line_items' => [[
                'price' => $price,
                'quantity' => 1,
                'tax_rates' => [
                    'txr_1Hsa41DPWXDL9ZlsaDU9Ksjr',
                ]
            ]]
        ]);

        return new JsonResponse(['sessionId' => $checkout_session['id']]);

    }
}
