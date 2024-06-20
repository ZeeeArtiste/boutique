<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
            'label' => 'Votre e-mail',
            'attr' => ['placeholder' => 'Mon e-mail...'
            ]
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Votre Prénom',
                'constraints'=> new Length([
                    'min'=>2,
                    'max'=>30
                ]),
                'attr'=>['placeholder'=> 'Mon prénom...'
                ]
            ])
            ->add('lastname', TextType::class, [
            'label' => 'Votre nom',
            'attr' => ['placeholder' => 'Mon nom...'
            ]
             ])
            ->add('password',RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message'=>"Ce n'est pas le même mot de passe",
                'label'=>'Votre mot de passe',
                'required'=>true,
                'first_options'=>[
                    'label'=>'Mot de passe',
                    'attr'=>['placeholder'=>'Saisir votre mot de passe...']
                ],
                'second_options'=>[
                    'label'=>'Confirmez votre mot de passe',
                'attr' => ['placeholder' => 'Confirmez votre mot de passe...']
                ],
                'attr'=>[
                    'placeholder'=>'Mon mot de passe'
                ]
            ])
            
            ->add('submit', SubmitType::class, [
               'label'=>"S'inscrire" 
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
