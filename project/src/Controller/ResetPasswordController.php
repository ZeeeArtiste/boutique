<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route(path: '/mdp-oublie', name: 'reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        if ($request->get('email')) {
          $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
        if ($user) {
            //enregistrer en bdd la demande de reset
            $resetPassword = new ResetPassword();
            $resetPassword->setUser($user);
            $resetPassword->setToken(uniqid());
            $resetPassword->setCreatedAt(new \DateTime());
            $this->entityManager->persist($resetPassword);
             $this->entityManager->flush();

             $domain = "http://omboutiquederensy.herokuapp.com";
             //envoyer email evec lien pour maj
             $url= $this->generateUrl('update_password', [
                 'token'=>$resetPassword->getToken()
             ]);
             $content ="Bonjour". $user->getFirstname().", voici le lien pour rénitialiser votre mot de passe 🔐 : ";
             $content.="<a href='$domain$url'>cliquez ici</a>";
            $mail=new Mail();
            $mail->send($user->getEmail(), $user->getFirstname().' '. $user->getLastname(), 'Rénitialiser mon mot de passe', $content);
            $this->addFlash('notice', 'Mail de rénitialisation envoyée 💌');
        }else{
             $this->addFlash('notice', 'Adresse e-mail inconnue 😭');
        }
        }
        return $this->render('reset_password/index.html.twig');
    }




    
    #[Route(path: '/modifier-mdp/{token}', name: 'update_password')]
    public function update(Request $request, $token, UserPasswordHasherInterface $encoder){

        $resetPassword=$this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
        if (!$resetPassword) {
        return $this->redirectToRoute('reset_password');
        }

        //vérifier si creadtAt = now - 3h
        $now = new \DateTime();
        if ($now > $resetPassword->getCreatedAt()->modify('+ 3 hour'))
        {
            $this->addFlash('notice', 'Votre demande à expirer 🤨');
            return $this->redirectToRoute('reset_password');
        }
        //vue pour changer mdp
        $form=$this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd=$form->get('new_password')->getData();
            //encodage mdp
            $new_pwd = $form->get('new_password')->getData();
            $password = $encoder->hashPassword($resetPassword->getUser(), $new_pwd);
    
            $resetPassword->getUser()->setPassword($password);
            //flush bdd
            $this->entityManager->flush();
    
            //redirection
            $this->addFlash('notice', "Votre mot de pase vient d'être mis à jour ✅");
            return $this->redirectToRoute('app_login');
        }
        

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
