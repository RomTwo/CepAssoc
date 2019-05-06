<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteRegisterController extends AbstractController
{
    public function index()
    {
        $account = new Account();
        return $this->render('site_register/index.html.twig');
    }
}
