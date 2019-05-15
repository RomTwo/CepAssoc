<?php

namespace App\Controller\administration;

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
        return $this->render('administration/adherents/adherents.html.twig', [
            'adherents' => $adherents,
        ]);
    }

    public function edit(Adherent $adherent, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminAdherentType::class, $adherent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_adherents');
        }

        return $this->render('administration/adherents/adherentsEdit.html.twig', [
            'adherent' => $adherent,
            'form' => $form->createView()
        ]);
    }

    public function register($id)
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $entityManager = $this->getDoctrine()->getManager();
        $adherent = $repository->findOneById($id);

        if (!$adherent) {
            return $this->redirectToRoute('error_404');
        }

        $adherent->setIsRegisteredInFFG(true);
        $entityManager->flush();

        return $this->redirectToRoute("admin_adherents");
    }

    public function delete($id)
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $entityManager = $this->getDoctrine()->getManager();
        $adherent = $repository->findOneById($id);

        $adherent->setIsDeleted(true);
        $entityManager->flush();

        return $this->redirectToRoute("admin_adherents");
    }

}
