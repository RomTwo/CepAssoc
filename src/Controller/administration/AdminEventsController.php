<?php

namespace App\Controller\administration;

use App\Entity\Document;
use App\Form\EventType;
use App\Entity\Event;
use App\Services\Utilitaires;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminEventsController extends AbstractController
{

    /**
     * Return all events and add an event when the form add event is submit
     *
     * @param Request $request
     * @param Utilitaires $utilitaires
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, Utilitaires $utilitaires)
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $files = $event->getDocuments()->toArray();
            $event->clearDocuments();

            // Check if there is a upload files
            if (count($files['name']) > 0) {
                for ($i = 0; $i < count($files['name']); $i++) {
                    $doc = new Document($utilitaires->addFile($files['name'][$i]), $files['name'][$i]->getClientOriginalName());
                    $event->setDocuments($doc);
                }
            }

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

    /**
     * Modify an event
     *
     * @param Request $request
     * @param $id
     * @param Utilitaires $utilitaires
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id, Utilitaires $utilitaires)
    {
        $manager = $this->getDoctrine()->getManager();
        $event = $manager->getRepository(Event::class)->find($id);

        if ($event) {
            $form = $this->createForm(EventType::class, $event);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $files = $event->getDocuments()->toArray();
                $event->clearDocuments();

                // Check if there is a upload files
                if (count($files['name']) > 0) {
                    for ($i = 0; $i < count($files['name']); $i++) {
                        $doc = new Document($utilitaires->addFile($files['name'][$i]), $files['name'][$i]->getClientOriginalName());
                        $event->setDocuments($doc);
                    }
                }

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
            'files' => $event->getDocuments()
        ));
    }

    /**
     * Delete an event
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $event = $manager->getRepository(Event::class)->find($id);

        if ($event) {
            $manager->remove($event);
            $manager->flush();
            $this->addFlash('success', "L'évènement vient d'être supprimé");
        } else {
            $this->addFlash('error', "L'évènement n'existe pas");
        }

        return $this->redirectToRoute('admin_events');
    }

}
