<?php

namespace App\Controller\administration;

use App\Entity\Event;
use App\Entity\Job;
use App\Form\EventType;
use App\Services\GenerateToken;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminEventsController extends AbstractController
{

    public function index(Request $request, GenerateToken $generateToken)
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        $token = $generateToken->generateCustomToken(120);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();
            $this->addFlash('success', 'évènement ajouté');
            return $this->redirectToRoute('admin_events');
        }

        return $this->render('administration/events/events.html.twig', array(
                'form' => $form->createView(),
                'token' => $token,
                'events' => $events
            )
        );
    }

}
