<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route(path: '/compte', name: 'account')]
    public function index(): Response
    {
        $lastOrders = $this->entityManager->getRepository(Order::class)->findLast();
        return $this->render('account/index.html.twig',[
            'lastOrders'=>$lastOrders
        ]);
    }
}
