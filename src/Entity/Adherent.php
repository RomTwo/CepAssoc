<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdherentRepository")
 */
class Adherent
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $sex;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="boolean")
     */
    private $judge;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isGAFJudge;

    /**
     * @ORM\Column(type="integer")
     */
    private $GAFJudgeLevel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isGAMJudge;

    /**
     * @ORM\Column(type="integer")
     */
    private $GAMJudgeLevel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $teamGYMJudge;

    /**
     * @ORM\Column(type="integer")
     */
    private $teamGYMJudgeLevel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wantsAJudgeTraining;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $volunteerForTrainingHelp;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $volunteerForClubLife;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $registrationCost;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $registrationType;

    /**
     * @ORM\Column(type="date")
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paymentFeesArePaid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRegisteredInGestGym;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Account", mappedBy="children")
     */
    private $parents;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="adherents")
     */
    private $event;

    public function __construct()
    {
        $this->parents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(string $sex): self
    {
        $this->sex = $sex;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getJudge(): ?bool
    {
        return $this->judge;
    }

    public function setJudge(bool $judge): self
    {
        $this->judge = $judge;

        return $this;
    }

    public function getGAFjudge(): ?bool
    {
        return $this->isGAFJudge;
    }

    public function setGAFjudge(bool $isGAFJudge): self
    {
        $this->isGAFJudge = $isGAFJudge;

        return $this;
    }

    public function getGAFJudgeLevel(): ?int
    {
        return $this->GAFJudgeLevel;
    }

    public function setGAFJudgeLevel(int $GAFJudgeLevel): self
    {
        $this->GAFJudgeLevel = $GAFJudgeLevel;

        return $this;
    }

    public function getGAMJudge(): ?bool
    {
        return $this->isGAMJudge;
    }

    public function setGAMJudge(bool $isGAMJudge): self
    {
        $this->isGAMJudge = $isGAMJudge;

        return $this;
    }

    public function getGAMJudgeLevel(): ?int
    {
        return $this->GAMJudgeLevel;
    }

    public function setGAMJudgeLevel(int $GAMJudgeLevel): self
    {
        $this->GAMJudgeLevel = $GAMJudgeLevel;

        return $this;
    }

    public function getteamGYMJudge(): ?bool
    {
        return $this->teamGYMJudge;
    }

    public function setteamGYMJudge(bool $teamGYMJudge): self
    {
        $this->teamGYMJudge = $teamGYMJudge;

        return $this;
    }

    public function getteamGYMJudgeLevel(): ?int
    {
        return $this->teamGYMJudgeLevel;
    }

    public function setteamGYMJudgeLevel(int $teamGYMJudgeLevel): self
    {
        $this->teamGYMJudgeLevel = $teamGYMJudgeLevel;

        return $this;
    }

    public function getWantsAJudgeTraining(): ?bool
    {
        return $this->wantsAJudgeTraining;
    }

    public function setWantsAJudgeTraining(bool $wantsAJudgeTraining): self
    {
        $this->wantsAJudgeTraining = $wantsAJudgeTraining;

        return $this;
    }

    public function getvolunteerForTrainingHelp(): ?string
    {
        return $this->volunteerForTrainingHelp;
    }

    public function setvolunteerForTrainingHelp(string $volunteerForTrainingHelp): self
    {
        $this->volunteerForTrainingHelp = $volunteerForTrainingHelp;

        return $this;
    }

    public function getVolunteerForClubLife(): ?string
    {
        return $this->volunteerForClubLife;
    }

    public function setVolunteerForClubLife(string $volunteerForClubLife): self
    {
        $this->volunteerForClubLife = $volunteerForClubLife;

        return $this;
    }

    public function getRegistrationCost(): ?float
    {
        return $this->registrationCost;
    }

    public function setRegistrationCost(?float $registrationCost): self
    {
        $this->registrationCost = $registrationCost;

        return $this;
    }

    public function getregistrationType(): ?string
    {
        return $this->registrationType;
    }

    public function setregistrationType(?string $registrationType): self
    {
        $this->registrationType = $registrationType;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getpaymentFeesArePaid(): ?bool
    {
        return $this->paymentFeesArePaid;
    }

    public function setpaymentFeesArePaid(bool $paymentFeesArePaid): self
    {
        $this->paymentFeesArePaid = $paymentFeesArePaid;

        return $this;
    }

    public function getisRegisteredInGestGym(): ?bool
    {
        return $this->isRegisteredInGestGym;
    }

    public function setisRegisteredInGestGym(bool $isRegisteredInGestGym): self
    {
        $this->isRegisteredInGestGym = $isRegisteredInGestGym;

        return $this;
    }

    /**
     * @return Collection|Account[]
     */
    public function getParentszz(): Collection
    {
        return $this->parents;
    }

    public function addParentszz(Account $parents): self
    {
        if (!$this->parents->contains($parents)) {
            $this->parents[] = $parents;
            $parents->addChild($this);
        }

        return $this;
    }

    public function removeParentszz(Account $parents): self
    {
        if ($this->parents->contains($parents)) {
            $this->parents->removeElement($parents);
            $parents->removeChild($this);
        }

        return $this;
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
}
