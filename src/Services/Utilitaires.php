<?php


namespace App\Services;

class Utilitaires
{
    public function setOtherFields($adherent){
        $adherent->setRegistrationDate(new \DateTime());
        $adherent->setIsRegisteredInGestGym(false);
        $adherent->setJudge(false);
        $adherent->setPaymentFeesArePaid(false);
        $adherent->setRegistrationCost(0);
        $adherent->setIsRegisteredInFFG(false);
        $adherent->setIsMedicalCertificate(false);
        $adherent->setIsValidateMedical(false);
        $adherent->setMedicalCertificateDate(new \DateTime("01-09-2019"));
        $adherent->setNationality("France");
        $adherent->setIsFFGInsurance(false);
        $adherent->setIsAllowEmail(false);
        $adherent->setIsLicenceHolderOtherClub(false);
        $adherent->setMaidenName("");
        $adherent->setMedicalCertificate("dede");
        $adherent->setBulletinN2Allianz("dede");
        $adherent->setHasBulletinN2Allianz(false);
        $adherent->setIsDeleted(false);

        return $adherent;
    }

    public function isValidateCity($name){
        if($name == null){
            return false;
        }

        return true;
    }
}