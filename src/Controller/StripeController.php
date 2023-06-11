<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

    class StripeController extends AbstractController
    {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/commande/create-session/{reference}', name: 'stripe_create_session')]
        public function index(EntityManagerInterface $entityManager, Cart $cart, $reference)
        {
            $product_for_stripe = [];
            $YOUR_DOMAIN = 'http://127.0.0.1:8000';

            $order=$entityManager->getRepository(Order::class)->findOneByReference($reference);

            if(!$order)
            {
                new JsonResponse(['error'=>'order']);
            }

            foreach ($order->getOrderDetails()->getValues() as $product) {
                $product_objetcs = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
                $product_for_stripe[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'unit_amount' => $product->getPrice(),
                        'product_data' => [
                            'name' => $product->getProduct(),
                            'images' => [$YOUR_DOMAIN . "/uploads/" . $product_objetcs->getImages()[0]],
                        ],
                    ],
                    'quantity' => $product->getQuantity(),
                ];
            }

        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => '1',
        ];

            Stripe::setApiKey('sk_test_51Jw1gQFV7IAJxTlba6PCwXyV77FzocrMceWJB6W4et06np696XW07P8jXlD8hVvScQvwgAbnUwPdRvBv9PyHJjpp00pmV0qNt0');

            $checkout_session = Session::create([
                'customer_email'=> $this->getUser()->getEmail(),
                'line_items' => [
                    $product_for_stripe
                ],
                'payment_method_types' => [
                    'card',
                ],
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',
                'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
            ]);

            $order->setStripeSessionId($checkout_session->id);
            $entityManager->flush();

            return $this->redirect($checkout_session->url);
        }
    }
