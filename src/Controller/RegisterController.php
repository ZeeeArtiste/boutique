<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/inscription', name: 'inscription')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification=null;
        $user=new User();
        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()) {
           $user=$form->getData();
           $search_email=$this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

           if(!$search_email){
                $password = $encoder->hashPassword($user, $user->getPassword());

                $user->setPassword($password);
                
                $this->entityManager->persist($user);

                $this->entityManager->flush();

                $mail = new Mail();
                $content = 'Petit message pour vous prévenir que vous êtes maintenant inscris chez nous ! 😉';
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur notre boutique 👕', $content);

                $notification = 'Inscription ok ! 😎';


           }else{

                $notification = 'Déja présent zebi !';
           }
           
            
            
        }

        return $this->render('inscription/index.html.twig', [
        
        'form'=>$form->createView(),
        'notification'=>$notification
    ]);
    }
}
