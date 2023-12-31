<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label'=>'Quel nom souhaitez-vous donner à votre adresse ?',
                'attr'=>[
                    'placeholder'=>'Nommez votre adresse'
                ]
            ])
            ->add( 'firstname', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'placeholder' => 'Entrez votre prénom'
            ]
        ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('company', TextType::class,[
                'label'=>'Quel est le nom de votre société (facultatif)',
                'required'=>false,
                'attr'=>[
                    'placeholder'=>'Nommez votre société'
                ]
            ])
            ->add('address', TextType::class,[
                'label'=>'Votre adresse',
                'attr'=>[
                    'placeholder'=>'5 rue du...'
                ]
            ])
            ->add('postal', TextType::class,[
                'label'=> 'Votre code postal',
                'attr'=>[
                    'placeholder'=> 'Entrez votre code postal'
                ]
            ])
            ->add('city', TextType::class,[
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>'Entrez votre ville'
                ]
            ])
            ->add('country', CountryType::class,[
                'label'=>'Pays',
                'attr'=>[
                    'placeholder'=>'Entrez votre pays'
                ]
            ])
            ->add('phone', telType::class,[
                'label'=>'Quel est votre numéro ?',
                'attr'=>[
                    'placeholder'=>'Votre numéro'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label'=>'valider',
                'attr'=>[
                    'class'=>'btn-block btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
