<?php

namespace App\EventSubscriber;

use App\Entity\Product;
use App\Entity\Transaction;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AbstractLifecycleEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private $userPasswordHasherInterface;
    private $entityManager;
    private $requestStack;


    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

        public static function getSubscribedEvents()
        {
            return [
            BeforeEntityPersistedEvent::class => 'UploadImages',
            BeforeEntityUpdatedEvent::class=>'UploadImages'
            ];
        }

        

    public function UploadImages(AbstractLifecycleEvent $event)
    {
        $entity = $event->getEntityInstance();
 

        if ($entity instanceof Product) 
        {
            for ($i=0; $i <count($entity->getImages()) ; $i++) { 
                $file = $entity->getImages()[$i]->getUploadFiles();

                if($file instanceof UploadedFile) {
                $filename = md5(uniqid()) . '.' . $file->getClientOriginalExtension();
                $file->move('uploads', $filename);
                $entity->getImages()[$i]->setPath($filename);
                $this->entityManager->persist(($entity));
                $this->entityManager->flush();
            }
            }
        }
    }
                    
                

}
