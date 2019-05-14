<?php

namespace App\Controller\administration;

use App\Entity\Event;
use App\Form\AdminAdherentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminEventsController extends AbstractController
{

    public function index()
    {
        return $this->render('administration/events/events.html.twig');
    }
}
