<?php

namespace App\Controller\administration;

use App\Entity\Event;
use App\Form\EventType;
use App\Services\GenerateToken;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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

    public function update(Request $request, $id, GenerateToken $generateToken)
    {
        $manager = $this->getDoctrine()->getManager();
        $event = $manager->getRepository(Event::class)->find($id);

        if ($event !== null) {
            $form = $this->createForm(EventType::class, $event);
            $form->handleRequest($request);
            $token = $generateToken->generateCustomToken(120);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->flush();
                $this->addFlash('success', "L'évènement vient d'être modifié");
                return $this->redirectToRoute('admin_events');
            }
        } else {
            $this->addFlash('danger', "L'évènement n'existe pas");
        }
        return $this->render('administration/events/updateEvent.html.twig', array(
            'form' => $form->createView(),
            'token' => $token,
        ));
    }

    public function delete($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $event = $manager->getRepository(Event::class)->find($id);

        if ($event !== null) {
            $manager->remove($event);
            $manager->flush();
            $this->addFlash('success', "L'évènement vient d'être supprimé");
        } else {
            $this->addFlash('error', "L'évènement n'existe pas");
        }

        return $this->redirectToRoute('admin_events');
    }

}
