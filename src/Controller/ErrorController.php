<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ErrorController extends AbstractController
{
    /**
     * Return the custom error page for the 404 error (Page Not Found)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function error404()
    {
        return $this->render('error/404.html.twig');
    }

    /**
     * Return the custom error page for the 403 error (Access Denied)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function error403()
    {
        return $this->render('error/403.html.twig');
    }
}