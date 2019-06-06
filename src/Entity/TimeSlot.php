<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\TimeSlotRepository")
 */
class TimeSlot
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Veuiller renseigner le jour")
     * @Assert\Regex(
     *     pattern = "/0|1|2|3|4|5|6/",
     *     match = true,
     *     message = "Le jour indiqué n'existe pas"
     * )
     */
    protected $day;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotNull(message="Veuiller renseigner le date de début")
     */
    private $startTime;

    /**
     * @ORM\Column(type="time")
     * @Assert\NotNull(message="Veuiller renseigner la date de fin")
     */
    private $endTime;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="timeSlot")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"competition"})
     */
    private $activity;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Adherent", mappedBy="timeSlots")
     */
    private $adherents;

    public function __construct()
    {
        $this->activity = new Activity();
        $this->adherents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDay(): ?int
    {
        return $this->day;
    }

    public function setDay(int $day): self
    {
        $this->day = $day;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getFullTime(): ?string
    {
        switch ($this->getDay()) {
            case 1:
                $day = "Lundi";
                break;
            case 2:
                $day = "Mardi";
                break;
            case 3:
                $day = "Mercredi";
                break;
            case 4:
                $day = "Jeudi";
                break;
            case 5:
                $day = "Vendredi";
                break;
            case 6:
                $day = "Samedi";
                break;
            case 0:
                $day = "Dimanche";
                break;
        }
        if ($this->getCity() != null)
            return $day . " de " . $this->getStartTime()->format(' H:i') . "/" . $this->getEndTime()->format(' H:i') . " à " . $this->getCity();
        else
            return $day . " de " . $this->getStartTime()->format(' H:i') . "/" . $this->getEndTime()->format(' H:i');
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection|Adherent[]
     */
    public function getAdherents(): Collection
    {
        return $this->adherents;
    }

    public function addAdherent(Adherent $adherent): self
    {
        if (!$this->adherents->contains($adherent)) {
            $this->adherents[] = $adherent;
            $adherent->addTimeSlot($this);
        }

        return $this;
    }

    public function removeAdherent(Adherent $adherent): self
    {
        if ($this->adherents->contains($adherent)) {
            $this->adherents->removeElement($adherent);
            $adherent->removeTimeSlot($this);
        }

        return $this;
    }

    public function getAdherentsNumber()
    {
        $cpt = 0;
        for ($i = 0; $i < sizeof($this->adherents); $i++) {
            if (!$this->adherents[$i]->getIsDeleted())
                $cpt = $cpt + 1;
        }
        return $cpt;
    }

    public function contains($adherent)
    {
        return $this->adherents->contains($adherent);
    }
}