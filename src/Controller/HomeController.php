<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Adherent;
use App\Form\AdherentFirstType;
use App\Form\AdherentSecondType;
use App\Form\AdherentThirdType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HomeController extends AbstractController
{

    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $errorCode = ""; $msg = ""; // will be use to print messages in the view

        // Getting a user's children --(adherents)
        $user = $this->getUser();
        $account = $this->getDoctrine()->getRepository(Account::class)->find($user->getId());

        $adherents = $account->getChildren();

        // Building the mini form for getting an affiliate code
        $defaultData = ['message' => 'Type your message here'];
        $form = $this->createFormBuilder($defaultData)
            ->add('code', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $code = $data['code'];

            // get the child associated to this affiliate code
            $adherent = $this->getDoctrine()->getRepository(Adherent::class)->findOneBy(['affiliateCode' => $code]);

            if($adherent == null){
                $errorCode = 1; // we signal the error
                $msg = "La clé que vous avez entré ne correspond à aucun adhérent";
            }
            else{
                // if the user already had the child, we do nothing and we mention it
                //if(isset($adherent, $adherents)){
                    $errorCode = 1; // we signal the error
                    $msg = "Vous suivez déjà l'adhérent correspondant au code entré";
                //}
                //else{
                    // Adding the child to the user
                    $account->addChild($adherent);
                    $errorCode = 0;
                    $msg = "Adhérent rajouté avec succès !";
                    $entityManager = $this->getDoctrine()->getManager();
                    //$entityManager->persist($account);
                    $entityManager->flush();
                //}

            }


            return $this->render('home/index.html.twig', ['adherents' => $adherents, 'form' => $form->createView(), 'code' => $code, 'errorCode' => $errorCode, 'msg' => $msg]);
        }

        return $this->render('home/index.html.twig', ['adherents' => $adherents, 'form' => $form->createView()]);
    }
}
