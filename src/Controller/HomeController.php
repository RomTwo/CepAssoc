<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdherentFirstType;
use App\Form\AdherentSecondType;
use App\Form\AdherentThirdType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request)
    {
        return $this->render('home/index.html.twig');
    }
}
