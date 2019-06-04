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

class AdminActivitiesCategoryController extends AbstractController
{

    public function index()
    {

        $repositoryCategory=$this->getDoctrine()->getRepository(Category::class);
        $categories=$repositoryCategory->findAll();

        return $this->render('administration/category/categories.html.twig', [
            'categories' => $categories,
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

        return $this->render('administration/category/categoryEdit.html.twig', [
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


        return $this->render('administration/category/categoryAdd.html.twig', [
            'category' => $category,
            'form' => $form->createView()]);
    }

    public function delete(Request $request,$id)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($id);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('sucess',"Category supprimée avec succès");
            return $this->redirectToRoute('admin_activities');
    }



}
