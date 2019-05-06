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
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !$this->findByEmail($account->getEmail())) {

            $manager = $this->getDoctrine()->getManager();
            $encoded = $encoder->encodePassword($account, $account->getSalt());
            $account->setPassword($encoded);
            $manager->persist($account);
            $manager->flush();

            return $this->render('home/index.html.twig');
        }

        return $this->render('account/index.html.twig', array(
            "form" => $form->createView()
        ));
    }


    private
    function findByEmail(string $email)
    {
        $account = null;
        $repo = $this->getDoctrine()->getRepository(Account::class);

        $account = $repo->findBy(array(
            'email' => $email
        ));

        return $account != null ? true : false;
    }

}
