<?php

namespace App\Form;

use App\Entity\Reviews;
use blackknight467\StarRatingBundle\Form\RatingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ReviewType extends AbstractType
{

    private $token;

    public function __construct(TokenStorageInterface $token)
    {
    $this->token = $token;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $disabled = $this->token->getToken() ? false :true;
        $builder
            ->add('rating', HiddenType::class)
            ->add('comment',null,[
                'label'=>"Votre commentaire :",
                'attr'=>[
                    "class"=>"my-3"
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label'=>$disabled?"Vous devez être connecté pour laisser un commentaire" : "Valider",
                'disabled'=>$disabled,
                'attr'=>[
                    "class"=>"btn btn-success",
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
