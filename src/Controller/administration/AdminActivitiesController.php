<?php

namespace App\Controller\administration;

use App\Entity\Activity;
use App\Entity\Category;
use App\Entity\Adherent;
use App\Entity\TimeSlot;
use App\Form\AdminActivityTimeSlotType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminActivitiesController extends AbstractController
{

    /**
     * Return all categories and activities
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $repositoryActivity = $this->getDoctrine()->getRepository(Activity::class);
        $activities = $repositoryActivity->findAll();

        $repositoryCategory = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repositoryCategory->findAll();

        return $this->render('administration/activities/activities.html.twig', array(
                'categories' => $categories,
                'activities' => $activities
            )
        );

    }

    /**
     * Creates a new activity
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addActivity(Request $request)
    {
        $activity = new Activity();
        $form = $this->createForm(AdminActivityTimeSlotType::class, $activity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $timeSlotsArray = $activity->getTimeSlot()->toArray();
            $em->persist($activity);

            foreach ($timeSlotsArray as $timeSlot) {
                $timeSlot->setActivity($activity);
                $em->persist($timeSlot);
            }

            $em->flush();
            $this->addFlash('success', "L'activité viens d'être ajoutée");
            return $this->redirectToRoute('admin_activities');
        }
        return $this->render('administration/activities/activityAddOrEdit.html.twig', array(
                'activity' => $activity,
                'form' => $form->createView()
            )
        );
    }

    /**
     * Update the activity having $id as ID
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editActivity($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $activity = $entityManager->getRepository(Activity::class)->find($id);

        $form = $this->createForm(AdminActivityTimeSlotType::class, $activity);
        $form->handleRequest($request);

        // if the activit exists
        if($activity) {
            // if the form is valid
            if ($form->isSubmitted() && $form->isValid()) {

                $timeSlotsArray = $activity->getTimeSlot()->toArray();
                foreach ($timeSlotsArray as $timeSlot) {
                    $timeSlot->setActivity($activity);
                    $entityManager->persist($timeSlot);
                }

                $entityManager->flush();
                $this->addFlash('success', "L'activité viens d'être modifiée");
                return $this->redirectToRoute('admin_activities');
            }

            return $this->render('administration/activities/activityAddOrEdit.html.twig', array(
                    'activity' => $activity,
                    'form' => $form->createView(),
                    'isEdition' => true
                )
            );
        }
        else{
            $this->addFlash('error', "L'activité n'existe pas");
            return $this->redirectToRoute('admin_activities');
        }

    }

    /**
     * Delete the activity having $id as ID
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteActivity($id)
    {
        $repositoryActivity = $this->getDoctrine()->getRepository(Activity::class);

        $activity = $repositoryActivity->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($activity);
        $entityManager->flush();
        $this->addFlash('sucess', "Activité supprimée avec succès");
        return $this->redirectToRoute('admin_activities');

    }

    /**
     * Return the timeslots and adherents of the activity having $id as ID
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function details($id)
    {
        $repositoryAdherant = $this->getDoctrine()->getRepository(Adherent::class);
        $adherants = $repositoryAdherant->findAll();

        $repositoryActivity = $this->getDoctrine()->getRepository(Activity::class);
        $activity = $repositoryActivity->findOneBy(['id' => $id]);
        $timeSlots = $activity->getTimeSlot();

        return $this->render('administration/activities/activityDetails.html.twig', array(
                'timeSlots' => $timeSlots,
                'activity' => $activity,
                'adherents' => $adherants
            )
        );
    }

    /**
     * Adding some adherents to the timeSlot using form parameters
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToTimeSlot(Request $request)
    {
        $repositoryAdherant = $this->getDoctrine()->getRepository(Adherent::class);

        // get activity Id
        $activityId = $request->get("activity");

        // $adherentsInOneLine contains all the ids of adherents selected
        $adherentsInOneLine = $request->get("hidden_framework");

        // if it's empty
        if ($adherentsInOneLine === "") {
            $this->addFlash("error", "Rien a été rajouté");
            return $this->redirectToRoute('admin_activityDetails', ["id" => $activityId]);
        }

        // putting each id in an array
        $adherents = explode(',', $adherentsInOneLine);

        //getting the timeSlot
        $timeSlotId = $request->get("timeSlot");
        $repositoryTimeSlot = $this->getDoctrine()->getRepository(TimeSlot::class);
        $timeSlot = $repositoryTimeSlot->findOneBy(['id' => $timeSlotId]);

        $em = $this->getDoctrine()->getManager();

        // get the adherents and adding them to the timeSlot
        foreach ($adherents as $adherentName) {
            $adherent = $repositoryAdherant->findOneBy(['id' => $adherentName]);
            $adherent->addTimeSlot($timeSlot);
        }
        $em->flush();

        $this->addFlash("success", "Ajout(s) effectués");
        return $this->redirectToRoute('admin_activityDetails', array(
                "id" => $activityId
            )
        );
    }

    /**
     * Delete the adherent having $adherentId as ID form the timeSlot having $timeSlotId as ID
     * of the activity having $activityId as ID
     * @param $activityId
     * @param $timeSlotId
     * @param $adherentId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteFromTimeSlot($activityId, $timeSlotId, $adherentId)
    {
        $repositoryTimeSlot = $this->getDoctrine()->getRepository(TimeSlot::class);
        $timeSlot = $repositoryTimeSlot->findOneBy(['id' => $timeSlotId]);

        $repositoryAdherant = $this->getDoctrine()->getRepository(Adherent::class);
        $adherent = $repositoryAdherant->findOneBy(['id' => $adherentId]);

        $timeSlot->removeAdherent($adherent);

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_activityDetails', array(
                "id" => $activityId
            )
        );
    }

}
