<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdminAdherentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminAccountsController extends AbstractController
{

    public function index()
    {
        return $this->render('administration/accounts.html.twig');
    }
}
