<?php

namespace App\Entity;

use App\Repository\ImagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagesRepository::class)]
class Images
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    private ?Product $product = null;

    private $uploadFiles;

    public function __toString(){
        return $this->path;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get the value of uploadFiles
     */ 
    public function getUploadFiles()
    {
        return $this->uploadFiles;
    }

    /**
     * Set the value of uploadFiles
     *
     * @return  self
     */ 
    public function setUploadFiles($uploadFiles)
    {
        $this->uploadFiles = $uploadFiles;

        return $this;
    }
}
