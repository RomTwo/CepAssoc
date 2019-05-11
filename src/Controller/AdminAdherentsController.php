<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdminAdherentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminAdherentsController extends AbstractController
{

    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $adherents = $repository->findAll();
        return $this->render('administration/adherents.html.twig', [
            'controller_name' => 'AdherentsController',
            'adherents' => $adherents,
        ]);
    }

    public function edit(Adherent $adherent, Request $request)
    {

        $form = $this->createForm(AdminAdherentType::class, $adherent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('admin_adherents');
        }

        return $this->render('administration/adherentsEdit.html.twig', [
            'adherent' => $adherent,
            'form' => $form->createView()
        ]);
    }

    public function register(Adherent $adherent, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $entityManager = $this->getDoctrine()->getManager();
        $adherent = $repository->findOneById($id);

        if (!$adherent) {
            return $this->redirectToRoute('error_404');
        }

        $adherent->setIsRegisteredInFFG(true);
        $entityManager->persist($adherent);
        $entityManager->flush();

        $adherents = $repository->findAll();
        return $this->render('administration/adherents.html.twig', [
            'adherents' => $adherents,
        ]);
    }
}
