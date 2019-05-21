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

        // Getting a user's children --(adherents)
        $user = $this->getUser();
        $account = $this->getDoctrine()->getRepository(Account::class)->find($user->getId());

        $adherents = $account->getChildren();

        // Building the mini form for getting an affiliate code
        $defaultData = [];
        $form = $this->createFormBuilder($defaultData)
            ->add('code', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "code"
            $data = $form->getData();
            $code = $data['code'];

            // get the child associated to this affiliate code
            $adherent = $this->getDoctrine()->getRepository(Adherent::class)->findOneBy(['affiliateCode' => $code]);

            if($adherent == null){
                $this->addFlash('error', "La clé que vous avez entré ne correspond à aucun adhérent");
            }
            else{
                // if the user already had the child, we do nothing and we mention it
                if($adherents->contains($adherent)){
                    $this->addFlash('error', "Vous suivez déjà l'adhérent correspondant au code entré");
                }
                else{
                    // Adding the child to the user
                    $account->addChild($adherent);
                    $this->addFlash('success', "Adhérent rajouté avec succès");
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                }

            }
            //return $this->render('home/index.html.twig', ['adherents' => $adherents, 'form' => $form->createView()]);
        }

        return $this->render('home/index.html.twig', ['adherents' => $adherents, 'form' => $form->createView()]);
    }
}
