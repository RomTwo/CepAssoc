<?php

namespace App\Controller\administration;

use App\Entity\Account;
use App\Form\AccountAdminType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function edit(Request $request, $id)
    {
        $manager = $this->getDoctrine()->getManager();

        $account = $manager->getRepository(Account::class)->find($id);
        if ($account !== null) {
            $form = $this->createForm(AccountAdminType::class, $account);

            if ($request->isMethod('POST') && $form->isValid()) {
                $manager->flush();
                $this->addFlash('success', "Le compte vient d'être modifié");
                return $this->redirectToRoute('admin_accounts');
            }
        } else {
            $this->addFlash('error', "Le compte n'existe pas");
        }

        return $this->render('administration/accounts/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * Delete a user account by id
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
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
