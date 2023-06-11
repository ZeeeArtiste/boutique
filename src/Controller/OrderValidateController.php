<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderValidateController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/commande/merci/{stripeSessionId}', name: 'order_validate')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);
        
        if(!$order || $order->getUser() != $this->getUser())
        {
            return $this->redirectToRoute('home');
        }
     
        if ($order->getState()==0)
        {
        //modifier statut commande isPaid->1
        $order->setState(1);
        //vider panier
        $cart->remove();
        $this->entityManager->flush();
            //envoyer un email
            $mail = new Mail();
            $content = 'Votre commande est validée ! 👌';
            $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(), 'Commande reçu !', $content);
        }

        //afficher info de la commande
        

        return $this->render('order_validate/index.html.twig', [
            'order'=>$order
        ]);
    }
}
