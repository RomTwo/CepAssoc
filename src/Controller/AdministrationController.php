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
use App\Entity\Activity;
use App\Entity\Event;
use App\Entity\Category;

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
    /**
     * @Route("/administration/category", name="addCategory")
     */
    public function addCategory(string $nameCategory)
    {
        $entityManager =$this->getDoctrine()->getManager();
        $category = new Category();
        $category->setName($nameCategory);
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/category/update", name="administration")
     */
    public function updateCategory($idCategory,string $nameCategory)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($idCategory);

        if (!$category) {
            throw $this->createNotFoundException(
                "category n'existe pas ".$idCategory
            );
        }

        $category->setName($nameCategory);
        $entityManager->flush();



        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/category/delete", name="administration")
     */
    public function supprimerCategory($idCategory)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($idCategory);

        $entityManager->remove($category);
        $entityManager->flush();

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/adherant/status/{id}", name="administration")
     */
    public function updateStatusAdherant($idAdherant)
    {
        $entityManager =$this->getDoctrine()->getManager();

        $adherant = new Adherent();
        $adherant = $this->getDoctrine()->getRepository(Adherent::class)->find($idAdherant);
        if (!$adherant) {
            throw $this->createNotFoundException(
                'Adherant non existant'.$idAdherant
            );
        }
        $adherant->setGAFjudge(1);
        $entityManager->flush();


        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/activity/add", name="administration")
     */
    public function addActivity(string $nameActivity,float $priceActivity,string $typeActivity,$idCategory)
    {
        $entityManager =$this->getDoctrine()->getManager();
        $activity = new Activity();

        $category = $entityManager->getRepository(Activity::class)->find($idCategory);

        $activity->setName($nameActivity);
        //     $activity->setStartDate($startDateActivity);
        $activity->setPrice($priceActivity);
        $activity->setType($typeActivity);

        $activity->setCategory($category);

        $entityManager->persist($activity);
        $entityManager->flush();

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/activity/update", name="administration")
     */
    public function updateActivity($idActivity,string $nameActivity,float $priceActivity,string $typeActivity,$idCategory)
    {

        $entityManager =$this->getDoctrine()->getManager();

        $activity = new Activity();
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($idActivity);

        $category = new Category();
        $category = $this->getDoctrine()->getRepository(Category::class)->find($idCategory);

        $activity->setName($nameActivity);
        //  $activity->setStartDate($startDateActivity);
        $activity->setPrice($priceActivity);
        $activity->setType($typeActivity);
        $activity->setCategory($category);

        $entityManager->flush();

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/activity/remove", name="administration")
     */
    public function deleteActivity($idActivity)
    {

        $entityManager =$this->getDoctrine()->getManager();

        $activity = new Activity();
        $activity = $this->getDoctrine()->getRepository(Activity::class)->find($idActivity);

        $entityManager->remove($activity);
        $entityManager->flush();

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/event/add", name="administration")
     */
    public function addEvent(string $nameEvent,string $adress,string $description,bool $authorizationOfOrganization)
    {
        $entityManager =$this->getDoctrine()->getManager();
        $event = new Event();
        $event-> setName($nameEvent);
        $event-> setDate();
        $event->setAddress($adress);
        $event->setDescription($description);
        $event->setAuthorizationOfOrganization($authorizationOfOrganization);
        $entityManager->persist($event);
        $entityManager->flush();

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/event/update", name="administration")
     */
    public function updateEvent($idEvent,string $nameEvent,string $adressEvent,string $descriptionEvent,bool $authorizationOfOrganization)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $event = $entityManager->getRepository(Event::class)->find($idEvent);

        if (!$event) {
            throw $this->createNotFoundException(
                "event n'existe pas ".$idEvent
            );
        }

        $event->setName($nameEvent);
        $event-> setDate();
        $event->setAddress($adressEvent);
        $event->setDescription($descriptionEvent);
        $event->setAuthorizationOfOrganization($authorizationOfOrganization);
        $entityManager->persist($event);
        $entityManager->flush();



        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }
    /**
     * @Route("/administration/event/remove", name="administration")
     */
    public function deleteEvent($idEvent)
    {

        $entityManager =$this->getDoctrine()->getManager();

        $event = new Event();
        $event = $this->getDoctrine()->getRepository(Event::class)->find($idEvent);

        $entityManager->remove($event);
        $entityManager->flush();

        return $this->render('administration/index.html.twig', [
            'controller_name' => 'AdministrationController',
        ]);
    }

}
