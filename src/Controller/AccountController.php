<?php

namespace App\Controller;

use App\Entity\Account;
use App\Form\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('home');
        }

        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !$this->findByEmail($account->getEmail())) {

            $manager = $this->getDoctrine()->getManager();
            $encoded = $encoder->encodePassword($account, $account->getPassword());
            $account->setPassword($encoded);
            $manager->persist($account);
            $manager->flush();

            return $this->redirectToRoute('connexion_security', array(), 301);
        }

        return $this->render('account/index.html.twig', array(
            "form" => $form->createView()
        ));
    }


    private function findByEmail(string $email)
    {
        $account = $this->getDoctrine()->getRepository(Account::class)->findBy(array(
            'email' => $email
        ));

        return $account != null ? true : false;
    }

}
