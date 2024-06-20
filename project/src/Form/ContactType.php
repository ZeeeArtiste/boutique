<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Nom', TextType::class, [
                'label'=>'Votre nom',
                'attr'=>[
                'placeholder'=>'Saisir votre nom'
                ]
             ])
            ->add('Prenom', TextType:: class, [
            'label' => 'Votre prénom',
            'attr' => [
                'placeholder' => 'Saisir votre prénom'
            ]
            ])
            ->add('E-mail', EmailType::class, [
                'label'=>'Votre e-mail',
            'attr' => [
                'placeholder' => 'Saisir votre e-mail'
            ]
            ])
             ->add('subject', TextType:: class, [
            'label' => 'Sujet',
            'attr' => [
                'placeholder' => 'Pourquoi vous nous contacter'
            ]
            ])
            ->add('message', TextareaType::class, [
                'label'=>'Votre message',
            'attr' => [
                'placeholder' => 'Saisir votre message'
            ]
            ])

            ->add('Content',SubmitType::class, [
                'label' => 'Envoyer',
            'attr' => [
                'class' => 'btn btn-block btn-success'
            ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
