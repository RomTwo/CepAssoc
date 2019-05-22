<?php

namespace App\Controller\administration;

use App\Entity\Event;
use App\Entity\EventManagement;
use App\Form\EventManagerType;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdminEventManagerController extends AbstractController
{
    public function index(Request $request, $id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $eventManagers = $this->getDoctrine()->getRepository(EventManagement::class)->findBy(array('event' => $event));

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
                'eventManagers' => $this->makeEventJson($eventManagers)
            )
        );
    }

    public function add(Request $request, $id)
    {

    }

    public function update(Request $request, $id)
    {

    }

    public function delete($id)
    {

    }

    public function makeEventJson($eventManagers)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $callback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime ? $innerObject->format('Y-m-d H:i:s') : '';
        };

        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $normalizer->setCallbacks(array('startDate' => $callback, 'endDate' => $callback));
        $encoder = new JsonEncoder();
        $serializer = new Serializer(array($normalizer), array($encoder));
        $data = $serializer->serialize($eventManagers, 'json', ['groups' => 'event']);
        return $data;
    }

}

