<?php

namespace App\Controller\administration;

use App\Entity\TimeSlot;
use App\Form\AdminAdherentType;
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

    public function add(TimeSlot $timeSlot)
    {
        $form = $this->createForm(TimeSlotType::class, $timeSlot);

        return $this->render('administration/timeSlots/timeSlotsAdd.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
