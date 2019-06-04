<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HealthQuestionnaireRepository")
 */
class HealthQuestionnaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasMemberOfFamilyDiedHeartAttack;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasPainChest;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasAsthma;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasLossOfConsciousness;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasResumptionOfSportWithoutDoctorConsent;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasMedicalTreatment;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasBoneProblem;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasHealthProblem;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $hasNeedMedicalAdvice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHasMemberOfFamilyDiedHeartAttack(): ?bool
    {
        return $this->hasMemberOfFamilyDiedHeartAttack;
    }

    public function setHasMemberOfFamilyDiedHeartAttack(?bool $hasMemberOfFamilyDiedHeartAttack): self
    {
        $this->hasMemberOfFamilyDiedHeartAttack = $hasMemberOfFamilyDiedHeartAttack;

        return $this;
    }

    public function getHasPainChest(): ?bool
    {
        return $this->hasPainChest;
    }

    public function setHasPainChest(?bool $hasPainChest): self
    {
        $this->hasPainChest = $hasPainChest;

        return $this;
    }

    public function getHasAsthma(): ?bool
    {
        return $this->hasAsthma;
    }

    public function setHasAsthma(?bool $hasAsthma): self
    {
        $this->hasAsthma = $hasAsthma;

        return $this;
    }

    public function getHasLossOfConsciousness(): ?bool
    {
        return $this->hasLossOfConsciousness;
    }

    public function setHasLossOfConsciousness(?bool $hasLossOfConsciousness): self
    {
        $this->hasLossOfConsciousness = $hasLossOfConsciousness;

        return $this;
    }

    public function getHasResumptionOfSportWithoutDoctorConsent(): ?bool
    {
        return $this->hasResumptionOfSportWithoutDoctorConsent;
    }

    public function setHasResumptionOfSportWithoutDoctorConsent(?bool $hasResumptionOfSportWithoutDoctorConsent): self
    {
        $this->hasResumptionOfSportWithoutDoctorConsent = $hasResumptionOfSportWithoutDoctorConsent;

        return $this;
    }

    public function getHasMedicalTreatment(): ?bool
    {
        return $this->hasMedicalTreatment;
    }

    public function setHasMedicalTreatment(?bool $hasMedicalTreatment): self
    {
        $this->hasMedicalTreatment = $hasMedicalTreatment;

        return $this;
    }

    public function getHasBoneProblem(): ?bool
    {
        return $this->hasBoneProblem;
    }

    public function setHasBoneProblem(?bool $hasBoneProblem): self
    {
        $this->hasBoneProblem = $hasBoneProblem;

        return $this;
    }

    public function getHasHealthProblem(): ?bool
    {
        return $this->hasHealthProblem;
    }

    public function setHasHealthProblem(?bool $hasHealthProblem): self
    {
        $this->hasHealthProblem = $hasHealthProblem;

        return $this;
    }

    public function getHasNeedMedicalAdvice(): ?bool
    {
        return $this->hasNeedMedicalAdvice;
    }

    public function setHasNeedMedicalAdvice(?bool $hasNeedMedicalAdvice): self
    {
        $this->hasNeedMedicalAdvice = $hasNeedMedicalAdvice;

        return $this;
    }


}
