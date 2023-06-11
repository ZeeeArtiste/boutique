<?php

namespace App\Form;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uploadFiles', FileType::class, [
            'label' => 'Image',
            'required' => false,
            'by_reference'=>false
        ])
        // ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
        //     $form = $event->getForm();
        //     $data = $event->getData();

        //     $parentForm = $form->getParent();
        //     $product = $parentForm->getData();

        //     dd( $form->get('uploadFiles')->getData());
        //     // Handle file upload
        //     $file = $form->get('path')->getData();
        //     if ($file instanceof UploadedFile) {
        //         $filename = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
        //         $file->move('uploads', $filename);
                
        //         // Update the 'path' field value with the uploaded file name
        //         $data->setPath($filename);
        //         $data->setProduct();
        //     }
        // });
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
