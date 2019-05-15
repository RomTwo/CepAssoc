<?php

namespace App\Controller\administration;

use App\Entity\Account;
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
        return $this->render('administration/accounts/accounts.html.twig', array('accounts' => $accounts));
    }

    public function delete($id)
    {

    }
}
