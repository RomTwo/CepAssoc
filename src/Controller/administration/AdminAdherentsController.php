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
        return $this->render('administration/adherents/adherents.html.twig', [
            'adherents' => $adherents,
        ]);
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
        /*$entityManager = $this->getDoctrine()->getManager();

        $form = $this->createForm(AdminAdherentType::class, $adherent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $utilitaires->isValidateCity($request->request->get("admin_adherent_cityRep1"))) {
            $adherent->setCityRep1($request->request->get("admin_adherent_cityRep1"));
            $utilitaires->setFiles($adherent);
            $entityManager->flush();
            return $this->redirectToRoute('admin_adherents');
        }

        return $this->render('administration/adherents/adherentsEdit.html.twig', [
            'adherent' => $adherent,
            'form' => $form->createView(),
            "cityRep1" => $adherent->getCityRep1(),
            "cityRep2" => $adherent->getCityRep2(),
            "adherent" => $adherent,
        ]);*/
        return $this->redirectToRoute("home");
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
        $adherent->setIsDeleted(true);
        $entityManager->flush();

        return $this->redirectToRoute("admin_adherents");
    }

    /**
     * generate a pdf containing the adherent data
     * @param $id is the id of the adherent whose data we want to print
     */
    public function generatePDF($id)
    {
        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $adherent = $repository->find($id);
        $html = $this->render('administration/adherents/generateAdherentsPDF.html.twig', [
            'adherent' => $adherent,
        ]);
        $pdfOptions = new Options();
        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($adherent->getFirstName() . "_" . $adherent->getLastName() . ".pdf", [
            "Attachment" => true
        ]);
    }
}
