<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/mon-panier', name: 'cart')]
    public function index(Cart $cart): Response
    {
        
        return $this->render('cart/index.html.twig',[
           'cart'=> $cart->getFull()
        ]);
    }

    #[Route(path: '/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id): Response
    {

        $cart->add($id);
        return $this->redirectToRoute('cart');
    }

    #[Route(path: '/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {

        $cart->remove();
        return $this->redirectToRoute('products');
    }

    #[Route(path: '/cart/delete/{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart, $id): Response
    {

        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    #[Route(path: '/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart, $id): Response
    {

        $cart->decrease($id);
        return $this->redirectToRoute('cart');
    }

  
}
