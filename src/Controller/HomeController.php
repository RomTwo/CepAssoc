<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdherentFirstType;
use App\Form\AdherentSecondType;
use App\Form\AdherentThirdType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request)
    {
        $adherent = new Adherent();

        $form = $this->createForm(AdherentFirstType::class, $adherent);
        $form2 = $this->createForm(AdherentSecondType::class, $adherent);
        $form3 = $this->createForm(AdherentThirdType::class, $adherent);


        $form->handleRequest($request);
        $form2->handleRequest($request);
        $form3->handleRequest($request);


        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $data = $form->getData();
            $form2->setData($data->getFirstName());
            return $this->render('home/index.html.twig', [
                'form' => $form2->createView(),
                'message' => $data->getFirstName(),
            ]);
        } else if ($form2->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $data = $form->getData();
            $form3->setData($data->getFirstName());
            return $this->render('home/index.html.twig', [
                'form' => $form3->createView(),
                'message' => $data->getFirstName(),
            ]);
        } else if ($form3->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $entityManager->flush();
        }

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'message' => "bonjour"
        ]);
    }
}
