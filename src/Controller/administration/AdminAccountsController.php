<?php

namespace App\Controller\administration;

use App\Entity\Account;
use App\Form\AccountAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminAccountsController extends AbstractController
{

    /**
     * Return all members of the site (each accounts)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $accounts = $this->getDoctrine()->getRepository(Account::class)->findAll();
        return $this->render('administration/accounts/accounts.html.twig', array('accounts' => $accounts));
    }

    /**
     * Update a user account by id
     * if the user has a ROLE_SUPER_ADMIN role, he can change the user role
     *
     * @param Request $request
     * @param $id
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, $id, UserPasswordEncoderInterface $encoder)
    {
        $manager = $this->getDoctrine()->getManager();
        $account = $manager->getRepository(Account::class)->find($id);
        $oldPassword = $account->getPassword();
        $oldRole = $account->getRoles();
        $errorRole = null;

        if ($account !== null) {
            $form = $this->createForm(AccountAdminType::class, $account);
            $form->handleRequest($request);

            $account->setPassword($oldPassword);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($oldPassword !== $account->getPassword()) {
                    $encoded = $encoder->encodePassword($account, $account->getPassword());
                    $account->setPassword($encoded);
                }
                if ($oldRole !== $account->getRoles() && !$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
                    $errorRole = "Vous n'avez pas l'autorisation de modifier le role d'un utilisateur";
                } else {
                    $manager->flush();
                    $this->addFlash('success', "Le compte vient d'être modifié");
                    return $this->redirectToRoute('admin_accounts');
                }
            }
        } else {
            $this->addFlash('error', "Le compte n'existe pas");
        }

        return $this->render('administration/accounts/edit.html.twig', array('form' => $form->createView(), 'errorRole' => $errorRole));
    }

    /**
     * Delete a user account by id
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public
    function delete($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $account = $manager->getRepository(Account::class)->find($id);

        if ($account !== null) {
            $manager->remove($account);
            $manager->flush();
            $this->addFlash('success', "Le compte vient d'être supprimé");
        } else {
            $this->addFlash('error', "Le compte n'existe pas");
        }

        return $this->redirectToRoute('admin_accounts');
    }
}
