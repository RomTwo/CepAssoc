<?php

namespace App\Controller\administration;

use App\Entity\Event;
use App\Form\EventType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminEventsController extends AbstractController
{

    public function index(Request $request)
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($event);
            $manager->flush();
            $this->addFlash('success', 'évènement ajouté');
            return $this->redirectToRoute('admin_events');
        }

        return $this->render('administration/events/events.html.twig', array(
                'form' => $form->createView(),
                'events' => $events
            )
        );
    }

    public function update(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $event = $manager->getRepository(Event::class)->find($id);

        if ($event !== null) {
            $form = $this->createForm(EventType::class, $event);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->flush();
                $this->addFlash('success', "L'évènement vient d'être modifié");
                return $this->redirectToRoute('admin_events');
            }
        } else {
            $this->addFlash('error', "L'évènement n'existe pas");
            return $this->redirectToRoute('admin_events');
        }
        return $this->render('administration/events/updateEvent.html.twig', array(
            'form' => $form->createView(),
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
