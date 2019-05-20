<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Activity;
use App\Entity\Adherent;
use App\Form\AdherentType;
use App\Services\Utilitaires;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;

class RegistrationController extends AbstractController
{
    public function index(Request $request, Utilitaires $utilitaires)
    {
        $adherent = new Adherent();

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        $form = $this->createForm(AdherentType::class, $adherent, array(
            'firstNameRep1'=>$user->getFirstName(),
            'lastNameRep1'=>$user->getLastName(),
            'emailRep1'=>$user->getEmail(),
            'addressRep1'=>$user->getAddress(),
            'zipCodeRep1'=>$user->getZipCode(),
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $utilitaires->isValidateCity($request->request->get("adherent_cityRep1"))) {
            $utilitaires->setOtherFields($adherent);
            $adherent->setAffiliateCode($this->generateAffiliateCode());
            $adherent->setCityRep1($request->request->get("adherent_cityRep1"));

            $account = $this->getDoctrine()->getRepository(Account::class)->find($user->getId());

            $account->addChild($adherent);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherent);
            $entityManager->flush();

            return $this->redirectToRoute('registration');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
            'activities' => $this->getDoctrine()
                ->getRepository(Activity::class)
                ->findAll(),
            'cityRep1' => $user->getCity(),
        ]);

    }

    private function generateAffiliateCode()
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $code = ""; // code that will be returned
        $adherentWithSameCode = -1; // this will contain an Adherent Entity instance having a certain given affiliateCode equals to $code

        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //characters list for code generation
        $charactersLength = strlen($characters);

        while ($adherentWithSameCode != null) {

            // generating a new code
            $code = "";
            for ($nbLetter = 0; $nbLetter < 5; $nbLetter++) {  // our code contain 5 characters !
                //$code .= chr(rand(65, 90));   // 65-90 range for upper case characters
                $code .= $characters[rand(0, $charactersLength - 1)];
            }

            $adherentWithSameCode = $repository->findOneBy(['affiliateCode' => $code]);
        }

        return $code;
    }

}
