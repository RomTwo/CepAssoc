<?php

namespace App\Controller\administration;

use App\Entity\Adherent;
use App\Form\AdminAdherentType;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Services\Utilitaires;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminAdherentsController extends AbstractController
{

    /**
     * get all adherents and send them to the view for display
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $adherents = $repository->findAll();
        return $this->render('administration/adherents/adherents.html.twig', array(
                'adherents' => $adherents,
            )
        );
    }

    /**
     *
     * @param Adherent $adherent who is being modified
     * @param Request $request
     * @param Utilitaires $utilitaires
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Adherent $adherent, Request $request, Utilitaires $utilitaires)
    {
        $entityManager = $this->getDoctrine()->getManager();
        //$oldAdherent = $entityManager->getRepository(Adherent::class)->find($adherent->getId());

        $form = $this->createForm(AdminAdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $utilitaires->isValidateCity($request->request->get("admin_adherent_cityRep1"))) {
            $adherent->setCityRep1($request->request->get("admin_adherent_cityRep1"));
            $utilitaires->setFiles($adherent);
            $entityManager->flush();
            $this->addFlash('success', $adherent->getFirstName() . " " . $adherent->getLastName() . " a été modifié avec succès");

            return $this->redirectToRoute('admin_adherents');
        }

        return $this->render('administration/adherents/adherentsEdit.html.twig', array(
                'adherent' => $adherent,
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Set an adherent 'isDeleted property' to true
     * @param $id is the id of the $adherent we want to remove
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $entityManager = $this->getDoctrine()->getManager();
        $adherent = $repository->findOneById($id);

        if ($adherent) {
            $adherent->setIsDeleted(true);
            $entityManager->flush();
            $this->addFlash('success', $adherent->getFirstName() . " " . $adherent->getLastName() . " a été supprimé de la liste des adhérents");
        } else {
            $this->addFlash('error', "L'adhérent n'existe pas");
        }


        return $this->redirectToRoute("admin_adherents");
    }

    /**
     * Edit the statut of the adherent
     * @param $id is the id of the $adherent we want to remove
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editStatus(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $entityManager = $this->getDoctrine()->getManager();

        $adherent = $repository->findOneById($id);
        $new_status = $request->query->get('status');

        if ($adherent != null && $new_status != null) {
            $adherent->setStatus($new_status);
            $entityManager->flush();
            $this->addFlash('success', "Le statut de " . $adherent->getFirstName() . " " . $adherent->getLastName() . " est maintenant de : " . $new_status);
        } else {
            $this->addFlash('error', "Erreur dans la requête.");
        }


        return $this->redirectToRoute("admin_adherents");
    }

    /**
     * Edit the GESTGYM statut of the adherent
     * @param $id is the id of the $adherent we want to remove
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editStatusGESTGYM(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $entityManager = $this->getDoctrine()->getManager();
        $adherent = $repository->findOneById($id);
        $new_status = $request->query->get('status');

        if ($adherent != null && $new_status != null) {
            $adherent->setIsRegisteredInGestGym($new_status);
            $entityManager->flush();
            $this->addFlash('success', "Le statut GESTGYM de " . $adherent->getFirstName() . " " . $adherent->getLastName() . " est maintenant de : " . $new_status);
        } else {
            $this->addFlash('error', "Erreur dans la requête.");
        }

        return $this->redirectToRoute("admin_adherents");
    }


    /**
     * generate a pdf containing the adherent data
     * @param $id is the id of the adherent whose data we want to print
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function generatePDF($id)
    {
        $user = $this->getUser();
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $adherent = $repository->find($id);

        if ($adherent || (($user->getRoles()[0] == "ROLE_ADMIN") || ($user->getRoles()[0] == "ROLE_SUPER_ADMIN"))) {
            $html = $this->render('administration/adherents/generateAdherentsPDF.html.twig', [
                'adherent' => $adherent,
            ])->getContent();
            $pdfOptions = new Options();
            $dompdf = new Dompdf($pdfOptions);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $dompdf->stream($adherent->getFirstName() . "_" . $adherent->getLastName() . ".pdf", [
                "Attachment" => true
            ]);
        } else {
            $this->addFlash('error', "Erreur dans la requête.");
            return $this->redirectToRoute("admin_adherents");
        }

    }
}
