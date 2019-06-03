<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Activity;
use App\Entity\Adherent;
use App\Entity\TimeSlot;
use App\Form\AdherentType;
use App\Services\Utilitaires;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class RegistrationController extends AbstractController
{
    public function index(Request $request, Utilitaires $utilitaires)
    {
        $adherent = new Adherent();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $account = $this->getDoctrine()->getRepository(Account::class)->findOneBy(
            array(
                'email' => $user->getEmail(),
            )
        );

        $form = $this->createForm(AdherentType::class, $adherent, array(
            'firstNameRep1' => $account->getFirstName(),
            'lastNameRep1' => $account->getLastName(),
            'emailRep1' => $account->getEmail(),
            'addressRep1' => $account->getAddress(),
            'zipCodeRep1' => $account->getZipCode(),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $utilitaires->isValidateCity($request->request->get("adherent_cityRep1"))) {
            $utilitaires->setOtherFields($adherent);
            $adherent->setAffiliateCode($this->generateAffiliateCode());
            $adherent->setCityRep1($request->request->get("adherent_cityRep1"));

            $account = $this->getDoctrine()->getRepository(Account::class)->find($user->getId());

            $account->addChild($adherent);
            if (!$this->isValidateHealthQuestionnaire($adherent->getHealthQuestionnaire())) {
                $adherent->setHealthQuestionnaire(null);
            } else {
                $this->generatePDF($adherent);
            }
            $this->setPrice($adherent,$request->request->get("selection"));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $entityManager->flush();

            $this->addFlash('success', "Votre adhérent à bien été ajouté");

            return $this->redirectToRoute('registration');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'activities' => $this->getDoctrine()
                ->getRepository(Activity::class)
                ->findAll(),
            'cityRep1' => $account->getCity(),
            'days' => array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi')
        ]);

    }

    private function generateAffiliateCode()
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $code = ""; // code that will be returned
        $adherentWithSameCode = -1; // this will contain an Adherent Entity instance having a certain given affiliateCode equals to $code

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //characters list for code generation
        $charactersLength = strlen($characters);

        while ($adherentWithSameCode != null) {

            // generating a new code
            $code = "";
            for ($nbLetter = 0; $nbLetter < 5; $nbLetter++) {  // our code contain 5 characters !
                //$code .= chr(rand(65, 90));   // 65-90 range for upper case characters
                $code .= $characters[rand(0, $charactersLength - 1)];
            }

            $adherentWithSameCode = $repository->findOneBy(['affiliateCode' => $code]);
        }

        return $code;
    }

    private function isValidateHealthQuestionnaire($healthQuestionnaire)
    {
        if ($healthQuestionnaire->getHasMemberOfFamilyDiedHeartAttack() === null) {
            return false;
        }

        if ($healthQuestionnaire->getHasPainChest() === null) {
            return false;
        }

        if ($healthQuestionnaire->getHasAsthma() === null) {
            return false;
        }

        if ($healthQuestionnaire->getHasLossOfConsciousness() === null) {
            return false;
        }

        if ($healthQuestionnaire->getHasResumptionOfSportWithoutDoctorConsent() === null) {
            return false;
        }

        if ($healthQuestionnaire->getHasMedicalTreatment() === null) {
            return false;
        }

        if ($healthQuestionnaire->getHasBoneProblem() === null) {
            return false;
        }

        if ($healthQuestionnaire->getHasHealthProblem() === null) {
            return false;
        }

        if ($healthQuestionnaire->getHasNeedMedicalAdvice() === null) {
            return false;
        }

        return true;
    }

    private function generatePDF($adherent)
    {
        $html = $this->render('account/generateHealthQuestionnairePDF.html.twig', [
            'adherent' => $adherent,
        ])->getContent();//Cette ligne permet de générer l'HTML d'une page twig.
        //L'option 'getContent()' permet quant à elle de générer cette page sans les informations
        //fournis par domPDF comme des informations de requêtes...
        $pdfOptions = new Options();
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = md5(uniqid()) . '.pdf';
        file_put_contents('uploads/' . $filename, $dompdf->output());
        $adherent->setHealthQuestionnaireFile($filename);
    }

    private function setPrice($adherent,$data)
    {
        $activities = array();
        foreach ($data as $value) {
            $timeSlot = $this->getDoctrine()->getRepository(TimeSlot::class)->find($value);
            if(!in_array($timeSlot->getActivity()->getId(), $activities, true)){
                array_push($activities, $timeSlot->getActivity()->getId());
            }
            $timeSlot->addAdherent($adherent);
        }
        $price = 0;
        foreach ($activities as $value){
            $activity = $this->getDoctrine()->getRepository(Activity::class)->find($value);
            $price += $activity->getPrice();
        }
        $adherent->setRegistrationCost($price);
    }

}
