<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventManagementRepository")
 */
class EventManagement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="eventManagements")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Veuiller renseigner l'évènement référent")
     */
    private $event;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Account", inversedBy="eventManagements")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Veuiller affecter un utilisateur")
     * @Groups({"event"})
     */
    private $account;

    /**
     * @ORM\Column(type="datetime", length=255)
     * @Assert\NotNull(message="Veuiller définir une date de départ")
     * @Assert\DateTime(message="Le format de la date de début n'est pas correct")
     * @Groups({"event"})
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime", length=255)
     * @Assert\NotNull(message="Veuiller définir une date de fin")
     * @Assert\DateTime(message="Le format de la date de fin n'est pas correct")
     * @Groups({"event"})
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Veuiller définir le rôle de la personne")
     * @Groups({"event"})
     */
    private $job;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"event"})
     */
    private $description;

    /**
     * EventManagement constructor.
     */
    public function __construct()
    {
        $this->place = null;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): self
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     */
    public function setJob($job)
    {
        $this->job = $job;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }




    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }


}
