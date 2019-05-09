<?php

namespace App\Controller;

use App\Entity\Adherent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AdministrationController extends AbstractController
{
    public function index()
    {
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }

    public function competiteurs()
    {
        $manager = $this->getDoctrine()->getManager();
        $competiteurs = $manager->getRepository(Adherent::class)->findAll();

        if ($competiteurs) {
            return JsonResponse::create($competiteurs, 202);
        }

        return JsonResponse::create("Aucuns compétiteurs pour le moment", 200);

    }
}
