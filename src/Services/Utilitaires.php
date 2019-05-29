<?php


namespace App\Services;

use App\Entity\Adherent;
use Dompdf\Dompdf;
use Dompdf\Options;
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

        if($adherent->getMedicalCertificate() != null){
            $adherent->setMedicalCertificate($this->addFile($adherent->getMedicalCertificate()));
        }

        if($adherent->getBulletinN2Allianz() != null){
            $adherent->setBulletinN2Allianz($this->addFile($adherent->getBulletinN2Allianz()));
        }

        if($adherent->getHealthQuestionnaireFile() != null){
            $adherent->setHealthQuestionnaireFile($this->addFile($adherent->getHealthQuestionnaireFile()));
        }

        return $adherent;
    }

    public function setFiles($adherent){
        if($adherent->getMedicalCertificate() != null && !is_string($adherent->getMedicalCertificate())){
            $adherent->setMedicalCertificate($this->addFile($adherent->getMedicalCertificate()));
        }

        if($adherent->getBulletinN2Allianz() != null && !is_string($adherent->getMedicalCertificate())){
            $adherent->setBulletinN2Allianz($this->addFile($adherent->getBulletinN2Allianz()));
        }

        if($adherent->getHealthQuestionnaire() != null && !is_string($adherent->getMedicalCertificate())){
            $adherent->setHealthQuestionnaire($this->addFile($adherent->getHealthQuestionnaire()));
        }

        return $adherent;
    }

    public function isValidateCity($name){
        if($name == null){
            return false;
        }

        return true;
    }

    private function addFile($file){
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->params->get('upload_directory'), $fileName);
        return $fileName;
    }

}