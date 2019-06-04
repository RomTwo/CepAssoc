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

    public function setOtherFields($adherent){
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

        if($adherent->getMedicalCertificateFile() != null){
            $adherent->setMedicalCertificateFile(new Document($this->addFile($adherent->getMedicalCertificateFile()), $adherent->getMedicalCertificateFile()->getClientOriginalName()));
        }

        if($adherent->getBulletinN2AllianzFile() != null){
            $adherent->setBulletinN2AllianzFile(new Document($this->addFile($adherent->getBulletinN2AllianzFile()), $adherent->getBulletinN2AllianzFile()->getClientOriginalName()));
        }

        if($adherent->getHealthQuestionnaireFile() != null){
            $adherent->setHealthQuestionnaireFile(new Document($this->addFile($adherent->getHealthQuestionnaireFile()), $adherent->getHealthQuestionnaireFile()->getClientOriginalName()));
        }

        return $adherent;
    }

    public function setFiles($adherent){
        if($adherent->getMedicalCertificateFile() != null && !$adherent->getMedicalCertificateFile() instanceof Document){
            $adherent->setMedicalCertificateFile(new Document($this->addFile($adherent->getMedicalCertificateFile()), $adherent->getMedicalCertificateFile()->getClientOriginalName()));
        }

        if($adherent->getBulletinN2AllianzFile() != null && !$adherent->getBulletinN2AllianzFile() instanceof Document){
            $adherent->setBulletinN2AllianzFile(new Document($this->addFile($adherent->getBulletinN2AllianzFile()), $adherent->getBulletinN2AllianzFile()->getClientOriginalName()));
        }

        if($adherent->getHealthQuestionnaireFile() != null && !$adherent->getHealthQuestionnaireFile() instanceof Document){
            $adherent->setHealthQuestionnaireFile(new Document($this->addFile($adherent->getHealthQuestionnaireFile()), $adherent->getHealthQuestionnaireFile()->getClientOriginalName()));
        }

        return $adherent;
    }

    public function isValidateCity($name){
        if($name == null){
            return false;
        }

        return true;
    }

    public function addFile($file)
    {
        $fileId = md5(uniqid());
        $file->move($this->params->get('upload_directory'), $fileId);
        return $fileId;
    }
}