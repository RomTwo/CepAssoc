<?php

namespace App\Controller\administration;

use App\Entity\TimeSlot;
use App\Form\TimeSlotType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminTimeSlotsController extends AbstractController
{

    public function index(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(TimeSlot::class);
        $timeSlots = $repository->findAll();

        $timeSlot = new TimeSlot();

        $form = $this->createForm(TimeSlotType::class, $timeSlot);
        $entityManager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // getting timeSlots with same values
            $identicalTimeSlots = $repository->findBy(
                array('startTime' => $timeSlot->getStartTime(),
                    'endTime' => $timeSlot->getEndTime(),
                    'city' => $timeSlot->getCity()
                ));

            // if such a timeSlot exist, we don't create a new one
            if (!empty($identicalTimeSlots)) {
                return $this->render('administration/timeSlots/timeSlots.html.twig',[
                    'timeSlots' => $timeSlots,
                    'form' => $form->createView(),
                    'err' => "Un créneau avec les mêmes attributs existe déjà, veuillez modifier les valeurs entrées !"  // to mention the error
                ]);
            }

            $entityManager->persist($timeSlot);
            $entityManager->flush();
            return $this->redirectToRoute("admin_timeSlots");
        }


        return $this->render('administration/timeSlots/timeSlots.html.twig',[
            'timeSlots' => $timeSlots,
            'form' => $form->createView()
        ]);
    }

    public function edit(TimeSlot $timeSlot, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(TimeSlotType::class, $timeSlot);

        $repository = $this->getDoctrine()->getRepository(TimeSlot::class);
        $timeSlots = $repository->findAll();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // getting timeSlots with same values
            $identicalTimeSlots = $repository->findBy(
                array('startTime' => $timeSlot->getStartTime(),
                    'endTime' => $timeSlot->getEndTime(),
                    'city' => $timeSlot->getCity()
                ));

            // if such a timeSlot exist, we don't create a new one
            if (!empty($identicalTimeSlots)) {
                return $this->render('administration/timeSlots/timeSlots.html.twig',[
                    'timeSlots' => $timeSlots,
                    'form' => $form->createView(),
                    'err' => "Un créneau avec les mêmes attributs existe déjà, veuillez modifier les valeurs entrées !",  // to mention the error
                    'isAModification' => true
                ]);
            }

            $entityManager->flush();
            return $this->redirectToRoute("admin_timeSlots");
        }

        return $this->render('administration/timeSlots/timeSlots.html.twig',[
            'timeSlots' => $timeSlots,
            'timeSlot' => $timeSlot,
            'form' => $form->createView(),
            'isAModification' => true       // specify that it is a modifiction because, we use the same view for the adding.
        ]);
    }

    public function delete(TimeSlot $timeSlot){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($timeSlot);
        $entityManager->flush();
        return $this->redirectToRoute('admin_timeSlots');
    }
}
