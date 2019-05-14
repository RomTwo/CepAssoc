<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Adherent;
use App\Form\AdminAdherentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminAccountsController extends AbstractController
{

    /**
     * Return all members of the site (each accounts)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $accounts = $this->getDoctrine()->getRepository(Account::class)->findAll();
        return $this->render('administration/accounts.html.twig', array('accounts' => $accounts));
    }
}
