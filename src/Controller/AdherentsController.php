<?php

namespace App\Controller;

use App\Entity\Adherent;
use App\Form\AdminAdherentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdherentsController extends AbstractController
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
        //$user = $this->getUser();
        
        /*$form = $this->createForm(AdminAdherentType::class, $adherent, array(
            'firstNameRep1'=>$adherent->getFirstName(),
            'lastNameRep1'=>$adherent->getLastName(),
            'emailRep1'=>$adherent->getEmailRep1(),
            'cityRep1'=>$adherent->getCityRep1(),
            'addressRep1'=>$adherent->getAddressRep1(),
            'zipCodeRep1'=>$adherent->getZipCodeRep1(),
        ));*/
        $form = $this->createForm(AdminAdherentType::class, $adherent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*$this->setOtherFields($adherent);
            $account = $this->getDoctrine()->getRepository(Account::class)->find($user->getId());

            $account->addChild($adherent);*/

            $entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('admin_adherents');
        }

        return $this->render('administration/adherentsEdit.html.twig', [
            'controller_name' => 'AdherentsController',
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
            'controller_name' => 'AdherentsController',
            'adherents' => $adherents,
        ]);
    }
}
