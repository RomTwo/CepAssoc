<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventManagement", mappedBy="events")
     */
    private $eventManagements;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Job", cascade={"persist"})
     */
    private $jobs;

    public function __construct()
    {
        $this->eventManagements = new ArrayCollection();
        $this->jobs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }


    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }


    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|EventManagement[]
     */
    public function getEventManagements(): Collection
    {
        return $this->eventManagements;
    }

    public function addEventManagement(EventManagement $eventManagement): self
    {
        if (!$this->eventManagements->contains($eventManagement)) {
            $this->eventManagements[] = $eventManagement;
            $eventManagement->setEvents($this);
        }

        return $this;
    }

    public function removeEventManagement(EventManagement $eventManagement): self
    {
        if ($this->eventManagements->contains($eventManagement)) {
            $this->eventManagements->removeElement($eventManagement);
            // set the owning side to null (unless already changed)
            if ($eventManagement->getEvents() === $this) {
                $eventManagement->setEvents(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Job[]
     */
    public function getJobs(): Collection
    {
        return $this->jobs;
    }

    public function addJob(Job $job): self
    {
        if (!$this->eventManagements->contains($job)) {
            $this->jobs[] = $job;
        }
        return $this;
    }

    public function removeJob(Job $job): self
    {
        if ($this->eventManagements->contains($job)) {
            $this->jobs->removeElement($job);
        }

        return $this;
    }


}
