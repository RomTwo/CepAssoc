<?php

namespace App\Controller\administration;

use App\Entity\Account;
use App\Entity\Event;
use App\Entity\EventManagement;
use App\Entity\Job;
use App\Form\EventManagerType;
use App\Services\CompareDatetime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AdminEventManagerController extends AbstractController
{
    /**
     * Return the form to add an event and an informations on the event
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index($id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        if ($event) {
            $newEventManagers = new EventManagement();
            $form = $this->createForm(EventManagerType::class, $newEventManagers, array(
                    'action' => $this->generateUrl('admin_event_manager_add'),
                    'method' => 'post',
                    'jobsEvent' => $event->getJobs()
                )
            );
            $form->add('idEvent', HiddenType::class, array(
                    'data' => $id,
                    'mapped' => false
                )
            );

        } else {
            $this->addFlash('error', "L'évènement n'existe pas");
            return $this->redirectToRoute('admin_events');
        }

        return $this->render('administration/eventManage/index.html.twig', array(
                'form' => $form->createView(),
                'idEvent' => $id,
                'startDate' => $event->getStartDate(),
                'endDate' => $event->getEndDate(),
            )
        );
    }

    /**
     * Return all timeslot add by users in this event and all jobs availaible for this event
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexFilter($id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        if (is_null($event)) {
            $this->addFlash('error', "L'évènement n'existe pas");
            return $this->redirectToRoute('admin_events');
        }

        $eventManagers = $this->getDoctrine()->getRepository(EventManagement::class)->findBy(array(
                'event' => $event
            )
        );

        return $this->render('administration/eventManage/filter.html.twig', array(
                'eventManagers' => $eventManagers,
                'jobs' => $event->getJobs()
            )
        );
    }

    /**
     * This function is call by an ajax request. It's permit to add an event dynamically
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param CompareDatetime $compareDatetime
     * @return JsonResponse
     */
    public function add(Request $request, ValidatorInterface $validator, CompareDatetime $compareDatetime)
    {
        // Check if the request is POST type
        if ($request->isMethod('POST')) {

            // Get all parameters of the form
            $eventId = $request->request->get('idEvent');
            $personId = $request->request->get('person');
            $start = $request->request->get('start');
            $end = $request->request->get('end');
            $jobId = $request->request->get('job');
            $description = $request->request->get('description');

            $manager = $this->getDoctrine()->getManager();

            $account = $manager->getRepository(Account::class)->find($personId);
            $event = $manager->getRepository(Event::class)->find($eventId);
            $job = $manager->getRepository(Job::class)->find($jobId);

            // Check if the datas is correct
            if (is_null($event)) {
                return JsonResponse::create("L'évènement n'existe pas", 404);
            } elseif (is_null($account)) {
                return JsonResponse::create("La personne n'existe pas", 404);
            } elseif (is_null($job)) {
                return JsonResponse::create("Le poste n'existe pas", 404);
            } elseif (!$compareDatetime->isSuperior($start, $end)) {
                return JsonResponse::create("La date de départ doit être inférieur à la date de fin", 400);
            } elseif (!$compareDatetime->isSuperior($event->getStartDate(), $start)) {
                return JsonResponse::create("La date de départ doit être supérieur à la date de début de l'évènement", 400);
            } elseif (!$compareDatetime->isSuperior($end, $event->getEndDate())) {
                return JsonResponse::create("La date de fin doit être inférieur à la date de fin de l'évènement", 400);
            }

            // Create an entity with informations entry by the user
            $eventManager = new EventManagement();
            $eventManager->setEvent($event);
            $eventManager->setAccount($account);
            $eventManager->setStartDate(new \DateTime($start));
            $eventManager->setEndDate(new \DateTime($end));
            $eventManager->setJob($job->getName());
            $eventManager->setDescription($description);

            // Call all assert of the entity for check if all datas is correct
            $errors = $validator->validate($eventManager);
            if (count($errors) > 0) {
                return JsonResponse::create($errors, 400);
            }
            $manager->persist($eventManager);
            $manager->flush();
        }
        // Return a success message if all it's good
        return JsonResponse::create('Ajout effectué', 200);
    }

    /**
     * This method is call by an ajax request. It's permit to update an event dynamically
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param CompareDatetime $compareDatetime
     * @return JsonResponse
     */
    public function update(Request $request, ValidatorInterface $validator, CompareDatetime $compareDatetime)
    {
        // Check if the request is POST type
        if ($request->isMethod('POST')) {

            // Get all parameters of the form
            $eventManagerId = $request->request->get('idEventManager');
            $personId = $request->request->get('person');
            $start = $request->request->get('start');
            $end = $request->request->get('end');
            $jobId = $request->request->get('job');
            $description = $request->request->get('description');

            $manager = $this->getDoctrine()->getManager();

            $eventManager = $manager->getRepository(EventManagement::class)->find($eventManagerId);
            $account = $manager->getRepository(Account::class)->find($personId);
            $job = $manager->getRepository(Job::class)->find($jobId);

            // Check if the datas is correct
            if (is_null($eventManager)) {
                return JsonResponse::create("L'évènement n'existe pas sur le calendrier", 404);
            } elseif (is_null($account)) {
                return JsonResponse::create("La personne n'existe pas", 404);
            } elseif (is_null($job)) {
                return JsonResponse::create("Le poste n'existe pas", 404);
            } elseif (!$compareDatetime->isSuperior($start, $end)) {
                return JsonResponse::create("La date de départ doit être inférieur à la date de fin", 400);
            } elseif (!$compareDatetime->isSuperior($eventManager->getEvent()->getStartDate(), $start)) {
                return JsonResponse::create("La date de départ doit être supérieur à la date de début de l'évènement", 400);
            } elseif (!$compareDatetime->isSuperior($end, $eventManager->getEvent()->getEndDate())) {
                return JsonResponse::create("La date de fin doit être inférieur à la date de fin de l'évènement", 400);
            }

            $eventManager->setAccount($account);
            $eventManager->setStartDate(new \DateTime($start));
            $eventManager->setEndDate(new \DateTime($end));
            $eventManager->setJob($job->getName());
            $eventManager->setDescription($description);

            // Call all assert of the entity for check if all datas is correct
            $errors = $validator->validate($eventManager);
            if (count($errors) > 0) {
                return JsonResponse::create($errors, 400);
            }
            $manager->flush();
        }
        // Return a success message if all it's good
        return JsonResponse::create('Mise à jour effectuée', 200);
    }

    /**
     * This method is call by an ajax request. It's permit to delete an event dynamically
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $id = $request->request->get('id');

        $manager = $this->getDoctrine()->getManager();
        $eventManager = $manager->getRepository(EventManagement::class)->find($id);

        if (is_null($eventManager)) {
            return JsonResponse::create("L'évènement n'existe pas", 404);
        }
        $manager->remove($eventManager);
        $manager->flush();

        return JsonResponse::create('Suppression terminée', 200);
    }

    /**
     * This method is call by an ajax request. This method return all timeslot add by users in this event and all jobs
     * availaible for this event at json format to fill the js calendar.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function events(Request $request)
    {
        $id = $request->query->get('id');

        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        if (is_null($event)) {
            return JsonResponse::create("L'évènement n'existe pas", 404);
        }

        $eventManagers = $this->getDoctrine()->getRepository(EventManagement::class)->findBy(array('event' => $event));
        $tab = array();

        foreach ($eventManagers as $e) {
            array_push($tab, array(
                    'id' => $e->getId(),
                    'title' => $e->getAccount()->getFullName() . ', ' . $e->getJob(),
                    'person' => $e->getAccount()->getFullName(),
                    'job' => $e->getJob(),
                    'description' => $e->getDescription(),
                    'start' => $e->getStartDate()->format('Y-m-d H:i:s'),
                    'end' => $e->getEndDate()->format('Y-m-d H:i:s'),
                )
            );
        }
        return JsonResponse::create($tab, 202);

    }

    /**
     * This method is call by an ajax request. It's permit to update just a date of event.
     * This method is used when the user move the timeslot on the calendar with your mouse.
     *
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param CompareDatetime $compareDatetime
     * @return JsonResponse
     */
    public function updateDatetime(Request $request, ValidatorInterface $validator, CompareDatetime $compareDatetime)
    {
        // Get all data of the form
        $id = $request->request->get('id');
        $start = $request->request->get('start');
        $end = $request->request->get('end');

        $manager = $this->getDoctrine()->getManager();
        $eventManager = $manager->getRepository(EventManagement::class)->find($id);

        // Check if dates is corrects
        if (!$compareDatetime->isSuperior($start, $end)) {
            return JsonResponse::create("La date de départ doit être inférieur à la date de fin", 400);
        } else if (is_null($eventManager)) {
            return JsonResponse::create("L'évènement n'existe pas sur le calendrier", 400);
        }

        $eventManager->setStartDate(new \DateTime($start));
        $eventManager->setEndDate(new \DateTime($end));

        // Invoke assert of the entity for check the type of data
        $errors = $validator->validate($eventManager);
        if (count($errors) > 0) {
            return JsonResponse::create($errors, 400);
        }
        $manager->flush();

        return JsonResponse::create('Mise à jour effectuée', 200);
    }

}
