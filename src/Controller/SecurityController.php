<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{

    public function login(): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('home');
        }
        return $this->render('security/login.html.twig');
    }

    public function loginPlugin()
    {
    }

    public function msg()
    {
        return JsonResponse::create("Salut mec sa va ??", 202);
    }

}
