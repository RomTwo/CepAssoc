<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Activity;
use App\Entity\Adherent;
use App\Form\AdherentType;
use App\Repository\AccountRepository;
use phpDocumentor\Reflection\Types\Parent_;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class RegistrationController extends AbstractController
{
    public function index(Request $request)
    {
        $adherent = new Adherent();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $form = $this->createForm(AdherentType::class, $adherent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adherent->setRegistrationDate(new DateTime());
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

            $account = $this->getDoctrine()->getRepository(Account::class)->find($user->getId());

            $account->addChild($adherent);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('registration');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'activities' => $this->getDoctrine()
                ->getRepository(Activity::class)
                ->findAll(),
            'user' => $user,
        ]);

    }
}
