<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Reviews;
use App\Form\ReviewType;
use App\Form\SearchType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }
    #[Route(path: '/nos-produits/{slug}', name: 'products')]
    public function index(Request $request, $slug = null): Response
    {
        $search=new Search();
        $form=$this->createForm(SearchType::class, $search);
        $form->handleRequest($request);

        if($slug){
            $category = $this->entityManager->getRepository(Category::class)->findOneBySlug($slug);
            $products = $this->entityManager->getRepository(Product::class)->findByCategory($category->getId());
            if(!$products)
            {
                return $this->redirectToRoute('products');
            }
    
        }else{
              $products = $this->entityManager->getRepository(Product::class)->findAll();
            }
        
         if ($form->isSubmitted() && $form->isValid()) {
             $products=$this->entityManager->getRepository(Product::class)->findWithSearch($search);
            }
        
        return $this->render('product/index.html.twig',[
            'products'=>$products,
            'form'=>$form->createView()
        ]);
    }


 #[Route(path: '/produit/{slug}', name: 'product')]
    public function show($slug, Request $request): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $product=$this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        $review = new Reviews();
        $form=$this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if(!$this->getUser()){
                return $this->redirectToRoute("app_login");
            }
        $review->setRating($form->getData()->getRating());
        $review->setCreatedAt(new DateTimeImmutable('now'));
        $review->setCreatedBy($this->getUser());
        $review->setProduct($product);

        $product->setTotalRating(($product->getTotalRating()*(count($product->getReviews()) == 0 ? 1 : count($product->getReviews())) + $review->getRating()) / (count($product->getReviews()) == 0 ? 1 : count($product->getReviews())+1));
        $this->entityManager->persist($product);
        $this->entityManager->persist($review);
        $this->entityManager->flush();
        return $this->redirectToRoute('product', ['slug'=>$slug, '_fragment'=>'reviews']);
        }

        if(!$product)
        {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig',[
            'product'=>$product,
            'products' => $products,
            'form'=>$form->createView()
        ]);
    }


    // #[Route(path: '/{cat}/produits', name: 'byCategory')]
    // public function showByCat($cat): Response
    // {
    //     $category = $this->entityManager->getRepository(Category::class)->findOneByName($cat);
    //     $products = $this->entityManager->getRepository(Product::class)->findByCategory($category->getId());

    //     if(!$products)
    //     {
    //         return $this->redirectToRoute('products');
    //     }

    //     return $this->render('product/show.html.twig',[
    //         'products' => $products
    //     ]);
    // }
}