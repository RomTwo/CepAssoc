<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SiteRegisterController extends AbstractController
{
    public function index()
    {
        return $this->render('site_register/index.html.twig');
    }
}
