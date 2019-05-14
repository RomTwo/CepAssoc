<?php

namespace App\Controller\administration;

use App\Entity\Account;
use App\Form\AdminAdherentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminAccountsController extends AbstractController
{

    public function index()
    {
        return $this->render('administration/accounts/accounts.html.twig');
    }
}
