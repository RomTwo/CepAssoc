<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Activity;
use App\Entity\Adherent;
use App\Entity\Document;
use App\Entity\TimeSlot;
use App\Form\AdherentType;
use App\Services\Utilitaires;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
            )
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $utilitaires->isValidateCity($request->request->get("adherent_cityRep1"))) {
            $utilitaires->setOtherFields($adherent);
            $adherent->setCityRep1($request->request->get("adherent_cityRep1"));

            $account = $this->getDoctrine()->getRepository(Account::class)->find($user->getId());

            $account->addChild($adherent);
            if (!$utilitaires->isValidateHealthQuestionnaire($adherent->getHealthQuestionnaire())) {
                $adherent->setHealthQuestionnaire(null);
            } else {
                $this->generatePDF($adherent);
            }
            $this->setPrice($adherent, $utilitaires->delimiter($request->request->get("idsOfTimeSlots")));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $entityManager->flush();

            $this->addFlash('success', "Votre adhérent à bien été ajouté");

            return $this->redirectToRoute('registration');
        }

        return $this->render('registration/index.html.twig', array(
                'form' => $form->createView(),
                'activities' => $this->getDoctrine()
                    ->getRepository(Activity::class)
                    ->findAll(),
                'cityRep1' => $account->getCity(),
                'days' => array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi')
            )
        );
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
        $fileId = md5(uniqid());
        file_put_contents('uploads/' . $fileId, $dompdf->output());
        $adherent->setHealthQuestionnaireFile(new Document($fileId, $adherent->getFirstName() . "_" . $adherent->getLastName() . "_QuestionnaireDeSante_CEPPoitiers.pdf"));
    }

    private function setPrice($adherent, $data)
    {
        if ($data) {
            $activities = array();
            foreach ($data as $value) {
                $timeSlot = $this->getDoctrine()->getRepository(TimeSlot::class)->find($value);
                if (!in_array($timeSlot->getActivity()->getId(), $activities, true)) {
                    array_push($activities, $timeSlot->getActivity()->getId());
                }
                $timeSlot->addAdherent($adherent);
            }
            $price = 0;
            foreach ($activities as $value) {
                $activity = $this->getDoctrine()->getRepository(Activity::class)->find($value);
                $price += $activity->getPrice();
            }
            $adherent->setRegistrationCost($price);
        }
    }

}
