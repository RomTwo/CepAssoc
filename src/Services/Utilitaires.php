<?php


namespace App\Services;

use App\Entity\Adherent;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Utilitaires extends AbstractController
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
        $adherent->setAffiliateCode($this->generateAffiliateCode());

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

        if($adherent->getBulletinN2Allianz() != null && !is_string($adherent->getBulletinN2Allianz())){
            $adherent->setBulletinN2Allianz($this->addFile($adherent->getBulletinN2Allianz()));
        }

        if($adherent->getHealthQuestionnaireFile() != null && !is_string($adherent->getHealthQuestionnaireFile())){
            $adherent->setHealthQuestionnaireFile($this->addFile($adherent->getHealthQuestionnaireFile()));
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

    private function generateAffiliateCode()
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $code = ""; // code that will be returned
        $adherentWithSameCode = -1; // this will contain an Adherent Entity instance having a certain given affiliateCode equals to $code
        $codeLength = 32;

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; //characters list for code generation
        $charactersLength = strlen($characters);

        while ($adherentWithSameCode != null) {

            // generating a new code
            $code = "";
            for ($nbLetter = 0; $nbLetter < $codeLength; $nbLetter++) {  // our code contain 5 characters !
                $code .= $characters[rand(0, $charactersLength - 1)];
            }

            $adherentWithSameCode = $repository->findOneBy(['affiliateCode' => $code]);
        }

        return $code;
    }

}