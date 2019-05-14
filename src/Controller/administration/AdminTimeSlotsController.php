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

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($timeSlot);
            $entityManager->flush();
        
            return $this->redirectToRoute('admin_timeSlots');
        }

        return $this->render('administration/timeSlots/timeSlotsAdd.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
