<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;


class AccountController extends AbstractController
{
    public function index(Request $request)
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if($form->isSubmitted()){


        }

        return $this->render('account/index.html.twig', array(
            "form" => $form->createView()
        ));
    }
}
