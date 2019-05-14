<?php

namespace App\Controller\administration;

use App\Entity\TimeSlot;
use App\Form\TimeSlotType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminTimeSlotsController extends AbstractController
{

    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(TimeSlot::class);
        $timeSlots = $repository->findAll();
        return $this->render('administration/timeSlots/timeSlots.html.twig',[
            'timeSlots' => $timeSlots,
        ]);
    }

    public function add(Request $request)
    {
        $timeSlot = new TimeSlot();

        $form = $this->createForm(TimeSlotType::class, $timeSlot);
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(TimeSlot::class);
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
                $err = "Un créneau avec les mêmes attributs existe déjà, veuillez en entrer un autre !";
                return $this->render('administration/timeSlots/timeSlotsAdd.html.twig',[
                    'form' => $form->createView(),
                    'err' => $err
                ]);
            }


            $entityManager->persist($timeSlot);
            $entityManager->flush();
        
            return $this->redirectToRoute('admin_timeSlots');
        }

        return $this->render('administration/timeSlots/timeSlotsAdd.html.twig',[
            'form' => $form->createView()
        ]);
    }

    public function edit(TimeSlot $timeSlot, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(TimeSlotType::class, $timeSlot);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_timeSlots');
        }

        return $this->render('administration/timeSlots/timeSlotsEdit.html.twig', [
            'timeSlot' => $timeSlot,
            'form' => $form->createView()
        ]);
    }

    public function delete(TimeSlot $timeSlot){
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($timeSlot);
        $entityManager->flush();
        return $this->redirectToRoute('admin_timeSlots');
    }
}
