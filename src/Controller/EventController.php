<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Event;
use App\Entity\EventManagement;
use App\Entity\Job;
use App\Services\CompareDatetime;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    public function index()
    {
        $account = $this->getCurrentUser();
        $events = $this->getDoctrine()->getRepository(Event::class)->findBy(array(), array('startDate' => 'DESC'));
        $eventsFollow = $this->getDoctrine()->getRepository(Event::class)->findByUser($account);

        return $this->render('events/index.html.twig', array(
                'events' => $events,
                'eventsFollow' => $eventsFollow
            )
        );
    }

    public function participate(Request $request, $id, CompareDatetime $compareDatetime)
    {

        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        if (is_null($event)) {
            $this->addFlash('error', "L'évènement n'existe pas");
            return $this->redirectToRoute('event_index');
        }

        $account = $this->getCurrentUser();
        if (is_null($account)) {
            $this->addFlash('error', "Vous n'êtes pas référencé dans la base de données");
            return $this->redirectToRoute('event_index');
        }

        $errors = null;

        if ($request->isMethod('POST')) {
            $ar = array();

            for ($i = 0; $i < count($_POST) / 3; $i++) {
                $start = $request->request->get('start' . $i);
                $end = $request->request->get('end' . $i);
                $job = $request->request->get('job' . $i);
                $jobEntity = $this->getDoctrine()->getRepository(Job::class)->find($job);

                if (is_null($start) || is_null($end) || is_null($job)) {
                    $errors = "Veuillez remplir tous les champs du formulaire";
                    break;
                } else if (is_null($jobEntity)) {
                    $errors = "la tâche saisie n'existe pas";
                    break;
                } else if (!$compareDatetime->isSuperior($start, $end)) {
                    $errors = 'La date de départ doit être inférieur à la date de fin';
                    break;
                } elseif (!$compareDatetime->isSuperior($event->getStartDate(), $start)) {
                    $errors = 'La date de départ doit être supérieur à la date de début de l\'évènement';
                    break;
                } elseif (!$compareDatetime->isSuperior($end, $event->getEndDate())) {
                    $errors = 'La date de fin doit être inférieur à la date de fin de l\'évènement';
                    break;
                }

                $eventManagement = new EventManagement();
                $eventManagement->setAccount($account);
                $eventManagement->setEvent($event);
                $eventManagement->setStartDate(new \DateTime($start));
                $eventManagement->setEndDate(new \DateTime($end));
                $eventManagement->setJob($jobEntity->getName());

                array_push($ar, $eventManagement);
            }

            if (is_null($errors)) {
                foreach ($ar as $em) {
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($em);
                    $manager->flush();
                }
                $this->addFlash('success', "Votre participation a bien été prise en compte");
                return $this->redirectToRoute('event_index');
            }
        }
        return $this->render('events/participate.html.twig', array(
                'event' => $event,
                'errors' => $errors,
                'jobsEvent' => $event->getJobs()
            )
        );
    }

    public function participateInfo($id)
    {
        $account = $this->getCurrentUser();
        if (is_null($account)) {
            $this->addFlash('error', "Vous n'êtes pas référencé dans la base de données");
            return $this->redirectToRoute('event_index');
        }

        $eventManager = $this->getDoctrine()->getRepository(EventManagement::class)->findBy(array(
                'account' => $account,
                'event' => $id
            )
        );

        if (is_null($eventManager)) {
            $this->addFlash('error', "L'évènement n'existe pas");
            return $this->redirectToRoute('event_index');
        }

        return $this->render('events/infoParticipateEvent.html.twig', array(
                'eventManagers' => $eventManager,
            )
        );

    }

    function getCurrentUser()
    {
        $currentUserEmail = $this->get('session')->get('_security.last_username');
        $account = $this->getDoctrine()->getRepository(Account::class)->findOneBy(array('email' => $currentUserEmail));
        return $account;
    }
}