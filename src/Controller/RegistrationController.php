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
    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
    {
        $adherent = new Adherent();
        $activity = new Activity();

        $form = $this->createForm(AdherentType::class, $adherent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adherent->setRegistrationDate(new DateTime());
            $adherent->setIsRegisteredInGestGym(false);
            $adherent->setJudge(false);
            $adherent->setPaymentFeesArePaid(false);
            $adherent->setRegistrationCost(0);

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
        ]);
    }

    /**
     * @Route("/add", name="add", methods="POST")
     */
    public function add(Request $request){

        $entityManager = $this->getDoctrine()->getManager();

        $adherent = new Adherent();
        $adherent->setFirstName($request->request->get('firstname'));
        $adherent->setLastName("test");
        $adherent->setSex("test");
        $adherent->setBirthDate(new DateTime());
        $adherent->setZipCode(11111);
        $adherent->setAddress("test");
        $adherent->setEmail("test");
        $adherent->setCity("test");
        $adherent->setJudge(false);
        $adherent->setGAFjudge(false);
        $adherent->setregistrationType("test");


        $entityManager->persist($adherent);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return $this->redirectToRoute('registration');
    }
}
