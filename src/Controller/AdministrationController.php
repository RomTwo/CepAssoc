<?php

namespace App\Controller;

use App\Entity\Adherent;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdministrationController extends AbstractController
{
    public function index()
    {
        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }

    public function competiteurs()
    {
        $manager = $this->getDoctrine()->getManager();
        $competiteurs = $manager->getRepository(Adherent::class)->findAll();


        if ($competiteurs) {
            $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

            $normalizer = new ObjectNormalizer($classMetadataFactory);
            $encoder = new JsonEncoder();
            $serializer = new Serializer(array($normalizer), array($encoder));
            $data = $serializer->serialize($competiteurs, 'json', ['groups' => 'competition']);

            return $this->render('administration/competiteurs.html.twig', array(
                "comp" => $data
            ));
        }
        return $this->render('administration/competiteurs.html.twig');
    }
}
