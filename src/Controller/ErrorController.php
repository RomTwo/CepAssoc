<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ErrorController extends AbstractController
{
    public function error404()
    {
        return $this->render('error/404.html.twig');
    }

    public function error403()
    {
        return $this->render('error/403.html.twig');
    }
}