<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdminAdherentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminEventsController extends AbstractController
{

    public function index()
    {
        return $this->render('administration/events.html.twig');
    }
}
