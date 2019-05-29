<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DocumentRepository")
 */
class Document
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=255)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\File(
     *     maxSize = "2048k",
     *     maxSizeMessage = "La taille du fichier est au-dessus de la limite",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Le fichier doit être sous format PDF"
     * )
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="documents")
     * @ORM\JoinColumn(nullable=true)
     */
    private $event;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginName(): ?string
    {
        return $this->originName;
    }

    public function setOriginName(string $originName): self
    {
        $this->originName = $originName;

        return $this;
    }

    public function getCryptName(): ?string
    {
        return $this->cryptName;
    }

    public function setCryptName(string $cryptName): self
    {
        $this->cryptName = $cryptName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }


}
