<?php

namespace App\Controller\administration;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Adherent;
use App\Entity\TimeSlot;
use App\Form\AdminActivityTimeSlotType;
use App\Form\AdminCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Time;

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


            $timeSlotsArray = $activity->getTimeSlot()->toArray();
            $em->persist($activity);

            foreach($timeSlotsArray as $timeSlot){
                $timeSlot->setActivity($activity);
                $em->persist($timeSlot);
            }

            $em->flush();
            return $this->redirectToRoute('admin_activities');
        }
        return $this->render('administration/activities/activityAddOrEdit.html.twig', [
            'activity' => $activity,
            'form' => $form->createView()]);
    }

    public function editActivity(Activity $activity, Request $request)
    {
        $form = $this->createForm(AdminActivityTimeSlotType::class, $activity);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $timeSlotsArray = $activity->getTimeSlot()->toArray();

            foreach($timeSlotsArray as $timeSlot){
                $timeSlot->setActivity($activity);
                $entityManager->persist($timeSlot);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_activities');
        }

        return $this->render('administration/activities/activityAddOrEdit.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
            'isEdition' => true
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

    public function details($id)
    {
        $repositoryAdherant=$this->getDoctrine()->getRepository(Adherent::class);
        $adherants=$repositoryAdherant->findAll();

        $repositoryActivity = $this->getDoctrine()->getRepository(Activity::class);
        $activity = $repositoryActivity->findOneBy(['id' => $id]);
        $timeSlots = $activity->getTimeSlot();

        return $this->render('administration/activities/activityDetails.html.twig', ['timeSlots' => $timeSlots, 'activity' => $activity, 'adherents' => $adherants] );
    }

    public function addToTimeSlot(Request $request)
    {
        $repositoryAdherant=$this->getDoctrine()->getRepository(Adherent::class);

        // get activity Id
        $activityId =  $request->get("activity");

        $adherentsInOneLine =  $request->get("hidden_framework");
        if($adherentsInOneLine === ""){
            $this->addFlash("error", "Rien a été rajouté");
            return $this->redirectToRoute('admin_activityDetails', ["id" => $activityId]);
        }

        $adherents = explode(',', $adherentsInOneLine);

        //getting the timeSlot
        $timeSlotId =  $request->get("timeSlot");
        $repositoryTimeSlot = $this->getDoctrine()->getRepository(TimeSlot::class);
        $timeSlot = $repositoryTimeSlot->findOneBy(['id' => $timeSlotId]);

        $em = $this->getDoctrine()->getManager();

        foreach($adherents as $adherentName){
            $adherent=$repositoryAdherant->findOneBy(['id' => $adherentName]);
            $adherent->addTimeSlot($timeSlot);
        }
        $em->flush();

        $this->addFlash("success", "Ajout(s) effectués");
        return $this->redirectToRoute('admin_activityDetails', ["id" => $activityId]);
    }

    public function deleteFromTimeSlot($activityId, $timeSlotId, $adherentId){
        $repositoryTimeSlot = $this->getDoctrine()->getRepository(TimeSlot::class);
        $timeSlot = $repositoryTimeSlot->findOneBy(['id' => $timeSlotId]);

        $repositoryAdherant=$this->getDoctrine()->getRepository(Adherent::class);
        $adherent=$repositoryAdherant->findOneBy(['id' => $adherentId]);

        $timeSlot->removeAdherent($adherent);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_activityDetails', ["id" => $activityId]);
    }

    public function copyTimeSlotEmails(Request $request){
        $jsonDatas = array();

        $allEmails = "";
        $timeSlotId = $request->request->get("timeSlotId");

        dump($timeSlotId);

        $repositoryTimeSlot = $this->getDoctrine()->getRepository(TimeSlot::class);

        // getting the timeSlot
        $timeSlot = $repositoryTimeSlot->findOneBy(["id" => $timeSlotId]);

        $adherents = $timeSlot->getAdherents()->toArray();
        foreach($adherents as $adherent){
            $allEmails .= $adherent->getEmailRep1() . "; ";
        }

        $jsonDatas[] = $allEmails;
        return new JsonResponse($jsonDatas);
    }

    public function copyAllEmails(Request $request){
        $jsonDatas = array();

        $allEmails = "";
        $activityId = (int)$request->request->get('activityId');

        $repositoryActivity = $this->getDoctrine()->getRepository(Activity::class);

        // getting the activity
        $activity = $repositoryActivity->find($activityId);

        // getting all the timeSlots connected to the activity
        $timeSlots = $activity->getTimeSlot();

        $adherentsCopied = array();
        foreach($timeSlots as $timeSlot){
            // for each timeSlot we get the adherents
            $adherents = $timeSlot->getAdherents()->toArray();
            foreach($adherents as $adherent){
                if(!in_array($adherent, $adherentsCopied)) {
                    $allEmails .= $adherent->getEmailRep1() . "; ";
                    $adherentsCopied[] = $adherent;
                }
            }
        }
        $jsonDatas[] = $allEmails;
        return new JsonResponse($jsonDatas);
    }

}
