<?php

namespace App\Controller\administration;

use App\Entity\Event;
use App\Entity\EventManagement;
use App\Form\EventManagerType;
use App\Services\GenerateToken;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminEventManagerController extends AbstractController
{
    public function index(Request $request, $id, GenerateToken $generateToken)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        if ($event !== null) {
            $newEventManagers = new EventManagement();
            $form = $this->createForm(EventManagerType::class, $newEventManagers, array('jobsEvent' => $event->getJobs()));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $newEventManagers->setEvent($event);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($newEventManagers);
                $manager->flush();

                $this->addFlash('success', 'Personne ajoutée');
                return $this->redirectToRoute('admin_event_manager_index', array('id' => $id));
            }
        } else {
            $this->addFlash('error', "L'évènement n'existe pas");
            return $this->redirectToRoute('admin_events');
        }

        return $this->render('administration/eventManage/index.html.twig', array(
                'form' => $form->createView(),
                'idEvent' => $id,
                'token' => $generateToken->generateCustomToken(120)
            )
        );
    }

    public function add(Request $request, $id)
    {

    }

    public function update(Request $request)
    {
        if ($request->isMethod('POST')) {
            $eventManagerId = $request->request->get('id');
            $person = $request->request->get('person');
            $start = $request->request->get('start');
            $end = $request->request->get('end');
            $job = $request->request->get('job');
            $place = $request->request->get('place');

        }
        return JsonResponse::create('okok', 200);
    }

    public function delete(Request $request)
    {
        $id = $request->request->get('id');

        $manager = $this->getDoctrine()->getManager();
        $eventManager = $manager->getRepository(EventManagement::class)->find($id);

        if ($eventManager === null) {
            return JsonResponse::create('L\'évènement n\'existe pas', 404);
        }
        $manager->remove($eventManager);
        $manager->flush();

        return JsonResponse::create('Effectué', 200);

    }

    public function events(Request $request)
    {
        $id = $request->query->get('id');
        $token = $request->query->get('token');

        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        if ($event === null) {
            return JsonResponse::create('L\'évènement n\'existe pas', 404);
        }

        try {
            JWT::decode($token, $_ENV['PRIVATE_KEY'], array($_ENV['ALG']));
            $eventManagers = $this->getDoctrine()->getRepository(EventManagement::class)->findBy(array('event' => $event));
            $tab = array();

            foreach ($eventManagers as $e) {
                array_push($tab, array(
                        'id' => $e->getId(),
                        'title' => $e->getAccount()->getFullName() . ', ' . $e->getJob(),
                        'person' => $e->getAccount()->getFullName(),
                        'job' => $e->getJob(),
                        'place' => $e->getPlace(),
                        'start' => $e->getStartDate()->format('Y-m-d H:i:s'),
                        'end' => $e->getEndDate()->format('Y-m-d H:i:s'),
                    )
                );
            }
            return JsonResponse::create($tab, 202);
        } catch (\Exception $e) {
            $e->getMessage();
            return JsonResponse::create('Une authentification est requise', 401);
        }
    }

}

