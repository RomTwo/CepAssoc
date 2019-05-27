<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Event;
use App\Entity\EventManagement;
use App\Form\EventManagerUserType;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    public function index()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return $this->render('events/index.html.twig', array(
            'events' => $events,
        ));
    }

    public function participate(Request $request, $id)
    {

        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        if (is_null($event)) {
            $this->addFlash('error', "L'évènement n'existe pas");
            return $this->redirectToRoute('event_index');
        }

        $currentUserEmail = $this->get('session')->get('_security.last_username');
        $account = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('email' => $currentUserEmail));

        if (is_null($account)) {
            $this->addFlash('error', "Vous n'êtes pas référencé dans la base de données");
            return $this->redirectToRoute('event_index');
        }

        $eventManagement = new EventManagement();
        $eventManagement->setAccount($account);
        $eventManagement->setEvent($event);

        $form = $this->createForm(EventManagerUserType::class, $eventManagement, array('jobsEvent' => $event->getJobs()));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($eventManagement);
            $manager->flush();

            $this->addFlash('success', "Votre participation a bien été prise en compte");
            return $this->redirectToRoute('event_index');
        }
        return $this->render('events/participate.html.twig', array(
            'form' => $form->createView(),
            'start' => $event->getStartDate(),
            'end' => $event->getEndDate(),
        ));
    }


}