<?php


namespace App\Services;

use App\Entity\Document;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Utilitaires
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    /*This function is used to add the default fields for a member.*/
    public function setOtherFields($adherent)
    {
        $adherent->setRegistrationDate(new \DateTime());
        $adherent->setIsRegisteredInGestGym(false);
        $adherent->setJudge(false);
        $adherent->setMedicalCertificateDate(new \DateTime("01-09-2019"));
        $adherent->setNationality("France");
        $adherent->setIsFFGInsurance(false);
        $adherent->setIsAllowEmail(false);
        $adherent->setIsLicenceHolderOtherClub(false);
        $adherent->setMaidenName("");
        $adherent->setHasBulletinN2Allianz(false);
        $adherent->setIsDeleted(false);
        $adherent->setHasMedicalCertificate(false);
        $adherent->setHasBulletinN2Allianz(false);
        $adherent->setHasHealthQuestionnaire(false);
        $adherent->setStatus("EN ATTENTE");
        $adherent->setAffiliateCode(md5(uniqid()));

        if ($adherent->getMedicalCertificateFile()) {
            $adherent->setMedicalCertificateFile(new Document($this->addFile($adherent->getMedicalCertificateFile()), $adherent->getMedicalCertificateFile()->getClientOriginalName()));
        }

        if ($adherent->getBulletinN2AllianzFile()) {
            $adherent->setBulletinN2AllianzFile(new Document($this->addFile($adherent->getBulletinN2AllianzFile()), $adherent->getBulletinN2AllianzFile()->getClientOriginalName()));
        }

        if ($adherent->getHealthQuestionnaireFile()) {
            $adherent->setHealthQuestionnaireFile(new Document($this->addFile($adherent->getHealthQuestionnaireFile()), $adherent->getHealthQuestionnaireFile()->getClientOriginalName()));
        }

        return $adherent;
    }

    /*This function is used when adding new files for a member.*/
    public function setFiles($adherent)
    {
        if ($adherent->getMedicalCertificateFile() && !$adherent->getMedicalCertificateFile() instanceof Document) {
            $adherent->setMedicalCertificateFile(new Document($this->addFile($adherent->getMedicalCertificateFile()), $adherent->getMedicalCertificateFile()->getClientOriginalName()));
        }

        if ($adherent->getBulletinN2AllianzFile() && !$adherent->getBulletinN2AllianzFile() instanceof Document) {
            $adherent->setBulletinN2AllianzFile(new Document($this->addFile($adherent->getBulletinN2AllianzFile()), $adherent->getBulletinN2AllianzFile()->getClientOriginalName()));
        }

        if ($adherent->getHealthQuestionnaireFile() && !$adherent->getHealthQuestionnaireFile() instanceof Document) {
            $adherent->setHealthQuestionnaireFile(new Document($this->addFile($adherent->getHealthQuestionnaireFile()), $adherent->getHealthQuestionnaireFile()->getClientOriginalName()));
        }

        return $adherent;
    }

    /*This function validates the city field.*/
    public function isValidateCity($name)
    {
        if ($name == null) {
            return false;
        }

        return true;
    }

    /*This function validates the health questionnaire.*/
    public function isValidateHealthQuestionnaire($healthQuestionnaire)
    {
        if (is_null($healthQuestionnaire->getHasMemberOfFamilyDiedHeartAttack()) ||
            is_null($healthQuestionnaire->getHasPainChest()) ||
            is_null($healthQuestionnaire->getHasAsthma()) ||
            is_null($healthQuestionnaire->getHasLossOfConsciousness()) ||
            is_null($healthQuestionnaire->getHasResumptionOfSportWithoutDoctorConsent()) ||
            is_null($healthQuestionnaire->getHasMedicalTreatment()) ||
            is_null($healthQuestionnaire->getHasBoneProblem()) ||
            is_null($healthQuestionnaire->getHasHealthProblem()) ||
            is_null($healthQuestionnaire->getHasNeedMedicalAdvice())
        ) {
            return false;
        }

        return true;
    }

    /*This function archives a file internally.*/
    public function addFile($file)
    {
        $fileId = md5(uniqid());
        $file->move($this->params->get('upload_directory'), $fileId);
        return $fileId;
    }

    /*This function makes it possible to separate the different ids during the selection of the slots.*/
    public
    function delimiter($data)
    {
        if ($data) {
            $idsOfTimeSlot = explode("/", $data, -1);
            return $idsOfTimeSlot;
        }

    }
}