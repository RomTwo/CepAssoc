<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\Event;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DocumentController extends AbstractController
{
    /**
     * Delete a document (picture, files...) with ajax request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $id = $request->request->get('id');

        $manager = $this->getDoctrine()->getManager();
        $document = $manager->getRepository(Document::class)->find($id);
        $event = $manager->getRepository(Event::class)->find($document->getEvent()->getId());

        if (is_null($document)) {
            return JsonResponse::create("Le document n'existe pas", 404);
        }
        $event->removeDocuments($document);
        $manager->remove($document);
        $manager->flush();

        return JsonResponse::create('Suppression terminée', 200);
    }
}
