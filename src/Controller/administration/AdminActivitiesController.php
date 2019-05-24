<?php

namespace App\Controller\administration;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Activity;
use App\Entity\Adherent;
use App\Entity\Category;
use App\Form\AdminActivityDetailsType;
use App\Form\AdminActivityTimeSlotType;
use App\Form\AdminAddAdherentToTimeSlotType;
use App\Form\AdminCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminActivitiesController extends AbstractController
{

    public function index()
    {
        $repositoryActivity=$this->getDoctrine()->getRepository(Activity::class);
        $activities=$repositoryActivity->findAll();

        $repositoryCategory=$this->getDoctrine()->getRepository(Category::class);
        $categories=$repositoryCategory->findAll();

        return $this->render('administration/activities/activities.html.twig', [
            'categories' => $categories,
            'activities' =>$activities
        ]);

    }

    public function edit(Category $category, Request $request)
    {
        $form = $this->createForm(AdminCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('admin_activities');
        }

        return $this->render('administration/activities/categoryEdit.html.twig', [
            'category' => $category,
            'form' => $form->createView()
        ]);


    }
    public function add(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(AdminCategoryType::class,$category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_activities');
        }


        return $this->render('administration/activities/categoryAdd.html.twig', [
            'category' => $category,
            'form' => $form->createView()]);
    }

    public function delete (Request $request,$id)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('sucess',"Category supprimée avec succès");
            return $this->redirectToRoute('admin_activities');


    }

    public function addActivity(Request $request)
    {
        $activity = new Activity();
        $form = $this->createForm(AdminActivityTimeSlotType::class,$activity);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $timeSlotsArray = $activity->timeSlot()->toArray();
            $timeSlotsSize = sizeof($timeSlotsArray);

            for($i = 0; $i < $timeSlotsSize; $i++){
                $em->persist($timeSlotsArray[$i]);
            }

            $em->persist($activity);
            $em->flush();
            return $this->redirectToRoute('admin_activities');
        }

        return $this->render('administration/activities/activityAdd.html.twig', [
            'activity' => $activity,
            'form' => $form->createView()]);
    }

    public function editActivity(Activity $activity, Request $request)
    {
        $form = $this->createForm(AdminActivityTimeSlotType::class, $activity);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $timeSlotsArray = $activity->timeSlot()->toArray();
            $timeSlotsSize = sizeof($timeSlotsArray);

            for($i = 0; $i < $timeSlotsSize; $i++){
                $entityManager->persist($timeSlotsArray[$i]);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_activities');
        }

        return $this->render('administration/activities/activityEdit.html.twig', [
            'activity' => $activity,
            'form' => $form->createView()
        ]);

    }

    public function deleteActivity(Request $request, $id)
    {

        $repositoryActivity = $this->getDoctrine()->getRepository(Activity::class);

        $activity = $repositoryActivity->find($id);


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($activity);
        $entityManager->flush();
        $this->addFlash('sucess', "Activité supprimée avec succès");
        return $this->redirectToRoute('admin_activities');

    }

    public function details(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $repositoryActivity = $this->getDoctrine()->getRepository(Activity::class);
        $activity = $repositoryActivity->findOneBy(['id' => $id]);
        $timeSlots = $activity->getTimeSlot();

        $forms = array();
        // first loop (forms bodys)
        $cpt = 0;
        foreach ($timeSlots as $timeSlot ){
            $form = $this->get('form.factory')->createNamedBuilder('name'.$cpt, AdminAddAdherentToTimeSlotType::class, $timeSlot)->getForm();
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                //$em->persist($timeSlot);
                $adherentsArray = $timeSlot->getAdherents()->toArray();
                $adherentsSize = sizeof($adherentsArray);
                for($i = 0; $i < $adherentsSize; $i++){
                    //$em->persist($adherentsArray[$i]);
                    //$timeSlots[0]->addAdherent($adherentsArray[$i]);
                    $adherentsArray[$i]->addTimeSlot($timeSlot);
                }
                $em->flush();

                return $this->redirectToRoute('admin_activityDetails', ['id' => $id]);
            }
            $form = $form->createView();
            $forms[] = $form;
            $cpt++;
        }

        return $this->render('administration/activities/activityDetails.html.twig', ['activity' => $activity, 'timeSlots' => $timeSlots, 'forms' => $forms]);
    }

    public function ajouter()
    {



        $category = new Category();
        $category->setName($_POST["hidden_framework"]);
        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $repository = $this->getDoctrine()->getRepository(Adherent::class);
        $adherents = $repository->findAll();


        return $this->render('administration/adherents/adherents.html.twig', [
            'adherents' => $adherents,
        ]);

    }


}
