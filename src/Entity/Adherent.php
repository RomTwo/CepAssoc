<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdherentRepository")
 */
class Adherent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"competition"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Veuiller remplir le champ nom")
     * @Groups({"competition"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="Veuiller remplir le champ nom")
     * @Groups({"competition"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotNull(message="Veuiller remplir le champ genre")
     * @Groups({"competition"})
     */
    private $sex;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotNull(message="Veuiller remplir le champ nom")
     * @Groups({"competition"})
     */
    private $birthDate;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $judge;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isGAFJudge;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"competition"})
     */
    private $GAFJudgeLevel;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isGAMJudge;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"competition"})
     */
    private $GAMJudgeLevel;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isTeamGYMJudge;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"competition"})
     */
    private $teamGYMJudgeLevel;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $wantsAJudgeTraining;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"competition"})
     */
    private $volunteerForTrainingHelp;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups({"competition"})
     */
    private $volunteerForClubLife;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups({"competition"})
     */
    private $registrationCost;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"competition"})
     */
    private $registrationType;

    /**
     * @ORM\Column(type="date")
     * @Groups({"competition"})
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $paymentFeesArePaid;

    /**
     * @ORM\Column(type="string")
     * @Groups({"competition"})
     */
    private $paymentType;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isRegisteredInGestGym;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=1, max=255)
     * @Groups({"competition"})
     */
    private $firstNameRep1;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competition"})
     */
    private $lastNameRep1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\Length(min=1, max=255)
     * @Groups({"competition"})
     */
    private $firstNameRep2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"competition"})
     */
    private $lastNameRep2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competition"})
     */
    private $emailRep1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"competition"})
     */
    private $emailRep2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competition"})
     */
    private $cityRep1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"competition"})
     */
    private $cityRep2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competition"})
     */
    private $addressRep1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"competition"})
     */
    private $addressRep2;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="Veuiller remplir le champ code postal")
     * @Assert\Regex(
     *     pattern = "/^([0-9]{2}|(2A)|2B)[[0-9]{3}$/",
     *     match = true,
     *     message = "le code postal n'est pas correct"
     * )
     * @Groups({"competition"})
     */
    private $zipCodeRep1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Regex(
     *     pattern = "/^([0-9]{2}|(2A)|2B)[[0-9]{3}$/",
     *     match = true,
     *     message = "le code postal n'est pas correct"
     * )
     */
    private $zipCodeRep2;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"competition"})
     */
    private $professionRep1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"competition"})
     */
    private $professionRep2;

    /**
     * @ORM\Column(type="integer", length=10)
     * @Groups({"competition"})
     */
    private $phoneRep1;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     * @Groups({"competition"})
     */
    private $phoneRep2;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $imageRight;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isRegisteredInFFG;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isMedicalCertificate;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Veuillez importer votre bulletin Allianz")
     * @Assert\File(mimeTypes={ "application/pdf" })
     * @Groups({"competition"})
     */
    private $medicalCertificate;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isValidateMedical;

    /**
     * @ORM\Column(type="date")
     * @Groups({"competition"})
     */
    private $medicalCertificateDate;

    /**
     * @ORM\Column(type="string")
     * @Groups({"competition"})
     */
    private $nationality;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isFFGInsurance;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isAllowEmail;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isLicenceHolderOtherClub;

    /**
     * @ORM\Column(type="string")
     * @Groups({"competition"})
     */
    private $maidenName;

    /**
     * @ORM\Column(type="string", nullable = true)
     * @Groups({"competition"})
     */
    private $volunteerComment;

    /**
     * @ORM\Column(type="string")
     * @Groups({"competition"})
     */
    private $bulletinN2Allianz;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $hasBulletinN2Allianz;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"competition"})
     */
    private $isDeleted;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     * @Groups({"competition"})
     */
    private $affiliateCode;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Activity")
     * @Groups({"competition"})
     */
    private $activities;

    public function __construct()
    {
        $this->activities = new ArrayCollection();
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

    public function getJudge(): ?bool
    {
        return $this->judge;
    }

    public function setJudge(bool $judge): self
    {
        $this->judge = $judge;

        return $this;
    }

    public function getIsGAFJudge(): ?bool
    {
        return $this->isGAFJudge;
    }

    public function setIsGAFJudge(bool $isGAFJudge): self
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

    public function getIsGAMJudge(): ?bool
    {
        return $this->isGAMJudge;
    }

    public function setIsGAMJudge(bool $isGAMJudge): self
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

    public function getIsTeamGYMJudge(): ?bool
    {
        return $this->isTeamGYMJudge;
    }

    public function setIsTeamGYMJudge(bool $isTeamGYMJudge): self
    {
        $this->isTeamGYMJudge = $isTeamGYMJudge;

        return $this;
    }

    public function getTeamGYMJudgeLevel(): ?int
    {
        return $this->teamGYMJudgeLevel;
    }

    public function setTeamGYMJudgeLevel(int $teamGYMJudgeLevel): self
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

    public function getVolunteerForTrainingHelp(): ?string
    {
        return $this->volunteerForTrainingHelp;
    }

    public function setVolunteerForTrainingHelp(string $volunteerForTrainingHelp): self
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

    public function getRegistrationType(): ?string
    {
        return $this->registrationType;
    }

    public function setRegistrationType(?string $registrationType): self
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

    public function getPaymentFeesArePaid(): ?bool
    {
        return $this->paymentFeesArePaid;
    }

    public function setPaymentFeesArePaid(bool $paymentFeesArePaid): self
    {
        $this->paymentFeesArePaid = $paymentFeesArePaid;

        return $this;
    }

    public function getIsRegisteredInGestGym(): ?bool
    {
        return $this->isRegisteredInGestGym;
    }

    public function setIsRegisteredInGestGym(bool $isRegisteredInGestGym): self
    {
        $this->isRegisteredInGestGym = $isRegisteredInGestGym;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstNameRep1()
    {
        return $this->firstNameRep1;
    }

    /**
     * @param mixed $firstNameRep1
     */
    public function setFirstNameRep1($firstNameRep1): void
    {
        $this->firstNameRep1 = $firstNameRep1;
    }

    /**
     * @return mixed
     */
    public function getLastNameRep1()
    {
        return $this->lastNameRep1;
    }

    /**
     * @param mixed $lastNameRep1
     */
    public function setLastNameRep1($lastNameRep1): void
    {
        $this->lastNameRep1 = $lastNameRep1;
    }

    /**
     * @return mixed
     */
    public function getFirstNameRep2()
    {
        return $this->firstNameRep2;
    }

    /**
     * @param mixed $firstNameRep2
     */
    public function setFirstNameRep2($firstNameRep2): void
    {
        $this->firstNameRep2 = $firstNameRep2;
    }

    /**
     * @return mixed
     */
    public function getLastNameRep2()
    {
        return $this->lastNameRep2;
    }

    /**
     * @param mixed $lastNameRep2
     */
    public function setLastNameRep2($lastNameRep2): void
    {
        $this->lastNameRep2 = $lastNameRep2;
    }

    /**
     * @return mixed
     */
    public function getEmailRep1()
    {
        return $this->emailRep1;
    }

    /**
     * @param mixed $emailRep1
     */
    public function setEmailRep1($emailRep1): void
    {
        $this->emailRep1 = $emailRep1;
    }

    /**
     * @return mixed
     */
    public function getEmailRep2()
    {
        return $this->emailRep2;
    }

    /**
     * @param mixed $emailRep2
     */
    public function setEmailRep2($emailRep2): void
    {
        $this->emailRep2 = $emailRep2;
    }

    /**
     * @return mixed
     */
    public function getCityRep1()
    {
        return $this->cityRep1;
    }

    /**
     * @param mixed $cityRep1
     */
    public function setCityRep1($cityRep1): void
    {
        $this->cityRep1 = $cityRep1;
    }

    /**
     * @return mixed
     */
    public function getCityRep2()
    {
        return $this->cityRep2;
    }

    /**
     * @param mixed $cityRep2
     */
    public function setCityRep2($cityRep2): void
    {
        $this->cityRep2 = $cityRep2;
    }

    /**
     * @return mixed
     */
    public function getAddressRep1()
    {
        return $this->addressRep1;
    }

    /**
     * @param mixed $addressRep1
     */
    public function setAddressRep1($addressRep1): void
    {
        $this->addressRep1 = $addressRep1;
    }

    /**
     * @return mixed
     */
    public function getAddressRep2()
    {
        return $this->addressRep2;
    }

    /**
     * @param mixed $addressRep2
     */
    public function setAddressRep2($addressRep2): void
    {
        $this->addressRep2 = $addressRep2;
    }

    /**
     * @return mixed
     */
    public function getZipCodeRep1()
    {
        return $this->zipCodeRep1;
    }

    /**
     * @param mixed $zipCodeRep1
     */
    public function setZipCodeRep1($zipCodeRep1): void
    {
        $this->zipCodeRep1 = $zipCodeRep1;
    }

    /**
     * @return mixed
     * @return Collection|Activity[]
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }


    public function addActivity(Activity $activity): self
    {

        if (!$this->activities->contains($activity)) {
            $this->activities[] = $activity;
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfessionRep1()
    {
        return $this->professionRep1;
    }

    public function removeActivity(Activity $activity): self
    {
        if ($this->activities->contains($activity)) {
            $this->activities->removeElement($activity);
        }
    }

    /**
     * @param mixed $professionRep1
     */
    public function setProfessionRep1($professionRep1): void
    {
        $this->professionRep1 = $professionRep1;
    }

    /**
     * @return mixed
     */
    public function getProfessionRep2()
    {
        return $this->professionRep2;
    }


    /**
     * @param mixed $professionRep2
     */
    public function setProfessionRep2($professionRep2): void
    {
        $this->professionRep2 = $professionRep2;
    }

    /**
     * @return mixed
     */
    public function getPhoneRep1()
    {
        return $this->phoneRep1;
    }

    /**
     * @param mixed $phoneRep1
     */
    public function setPhoneRep1($phoneRep1): void
    {
        $this->phoneRep1 = $phoneRep1;
    }

    /**
     * @return mixed
     */
    public function getPhoneRep2()
    {
        return $this->phoneRep2;
    }

    /**
     * @param mixed $phoneRep2
     */
    public function setPhoneRep2($phoneRep2): void
    {
        $this->phoneRep2 = $phoneRep2;
    }

    /**
     * @return mixed
     */
    public function getZipCodeRep2()
    {
        return $this->zipCodeRep2;
    }

    /**
     * @param mixed $zipCodeRep2
     */
    public function setZipCodeRep2($zipCodeRep2): void
    {
        $this->zipCodeRep2 = $zipCodeRep2;
    }

    /**
     * @return mixed
     */
    public function getPaymentType()
    {
        return $this->paymentType;
    }

    /**
     * @param mixed $paymentType
     */
    public function setPaymentType($paymentType): void
    {
        $this->paymentType = $paymentType;
    }

    /**
     * @return mixed
     */
    public function getImageRight()
    {
        return $this->imageRight;
    }

    /**
     * @param mixed $imageRight
     */
    public function setImageRight($imageRight): void
    {
        $this->imageRight = $imageRight;
    }

    /**
     * @return mixed
     */
    public function getIsRegisteredInFFG()
    {
        return $this->isRegisteredInFFG;
    }

    /**
     * @param mixed $isRegisteredInFFG
     */
    public function setIsRegisteredInFFG($isRegisteredInFFG): void
    {
        $this->isRegisteredInFFG = $isRegisteredInFFG;
    }

    /**
     * @return mixed
     */
    public function getIsMedicalCertificate()
    {
        return $this->isMedicalCertificate;
    }

    /**
     * @param mixed $isMedicalCertificate
     */
    public function setIsMedicalCertificate($isMedicalCertificate): void
    {
        $this->isMedicalCertificate = $isMedicalCertificate;
    }

    /**
     * @return mixed
     */
    public function getIsValidateMedical()
    {
        return $this->isValidateMedical;
    }

    /**
     * @param mixed $isValidateMedical
     */
    public function setIsValidateMedical($isValidateMedical): void
    {
        $this->isValidateMedical = $isValidateMedical;
    }

    /**
     * @return mixed
     */
    public function getMedicalCertificateDate()
    {
        return $this->medicalCertificateDate;
    }

    /**
     * @param mixed $medicalCertificateDate
     */
    public function setMedicalCertificateDate($medicalCertificateDate): void
    {
        $this->medicalCertificateDate = $medicalCertificateDate;
    }

    /**
     * @return mixed
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param mixed $nationality
     */
    public function setNationality($nationality): void
    {
        $this->nationality = $nationality;
    }

    /**
     * @return mixed
     */
    public function getIsFFGInsurance()
    {
        return $this->isFFGInsurance;
    }

    /**
     * @param mixed $isFFGInsurance
     */
    public function setIsFFGInsurance($isFFGInsurance): void
    {
        $this->isFFGInsurance = $isFFGInsurance;
    }

    /**
     * @return mixed
     */
    public function getIsAllowEmail()
    {
        return $this->isAllowEmail;
    }

    /**
     * @param mixed $isAllowEmail
     */
    public function setIsAllowEmail($isAllowEmail): void
    {
        $this->isAllowEmail = $isAllowEmail;
    }

    /**
     * @return mixed
     */
    public function getIsLicenceHolderOtherClub()
    {
        return $this->isLicenceHolderOtherClub;
    }

    /**
     * @param mixed $isLicenceHolderOtherClub
     */
    public function setIsLicenceHolderOtherClub($isLicenceHolderOtherClub): void
    {
        $this->isLicenceHolderOtherClub = $isLicenceHolderOtherClub;
    }

    /**
     * @return mixed
     */
    public function getMaidenName()
    {
        return $this->maidenName;
    }

    /**
     * @param mixed $maidenName
     */
    public function setMaidenName($maidenName): void
    {
        $this->maidenName = $maidenName;
    }

    public function getHasBulletinN2Allianz(): ?bool
    {
        return $this->hasBulletinN2Allianz;
    }

    public function setHasBulletinN2Allianz(bool $hasBulletinN2Allianz): self
    {
        $this->hasBulletinN2Allianz = $hasBulletinN2Allianz;

        return $this;
    }

    public function getHasCompetitionCommitment(): ?bool
    {
        return $this->hasCompetitionCommitment;
    }

    public function setHasCompetitionCommitment(bool $hasCompetitionCommitment): self
    {
        $this->hasCompetitionCommitment = $hasCompetitionCommitment;

        return $this;
    }

    public function getIsMutated(): ?bool
    {
        return $this->isMutated;
    }

    public function setIsMutated(bool $isMutated): self
    {
        $this->isMutated = $isMutated;

        return $this;
    }

    public function getTreatedBy(): ?string
    {
        return $this->treatedBy;
    }

    /**
     * @return mixed
     */
    public function getVolunteerComment()
    {
        return $this->volunteerComment;
    }

    /**
     * @param mixed $volunteerComment
     */
    public function setVolunteerComment($volunteerComment): void
    {
        $this->volunteerComment = $volunteerComment;
    }

    /**
     * @return mixed
     */
    public function getMedicalCertificate()
    {
        return $this->medicalCertificate;
    }

    /**
     * @param mixed $medicalCertificate
     */
    public function setMedicalCertificate($medicalCertificate): void
    {
        $this->medicalCertificate = $medicalCertificate;
    }

    public function setTreatedBy(?string $treatedBy): self
    {
        $this->treatedBy = $treatedBy;

        return $this;
    }

    public function getAffiliateCode(): ?string
    {
        return $this->affiliateCode;
    }

    public function setAffiliateCode(?string $affiliateCode): self
    {
        $this->affiliateCode = $affiliateCode;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBulletinN2Allianz()
    {
        return $this->bulletinN2Allianz;
    }

    /**
     * @param mixed $bulletinN2Allianz
     */
    public function setBulletinN2Allianz($bulletinN2Allianz): void
    {
        $this->bulletinN2Allianz = $bulletinN2Allianz;
    }


}
