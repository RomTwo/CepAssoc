<?php

namespace App\Controller\administration;

use App\Entity\Category;
use App\Form\AdminCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminActivitiesCategoryController extends AbstractController
{

    public function index(Request $request)
    {

        $repositoryCategory = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repositoryCategory->findAll();
        $category = new Category();
        $form = $this->createForm(AdminCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', "La catégorie " . $category->getName() . "a été ajoutée.");

            return $this->redirectToRoute('admin_categories');
        }
        return $this->render('administration/category/categories.html.twig', array(
                'categories' => $categories,
                'form' => $form->createView()
            )
        );

    }

    public function edit(Category $category, Request $request)
    {
        $form = $this->createForm(AdminCategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $this->addFlash('success', "La catégorie " . $category->getName() . "a été modifiée.");

            return $this->redirectToRoute('admin_categories');
        }


        return $this->render('administration/category/categoryEdit.html.twig', array(
                'category' => $category,
                'form' => $form->createView()
            )
        );


    }

    public function delete(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $category = $repository->find($id);

        if ($category) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('sucess', "Categorie supprimée avec succès");
        } else {
            $this->addFlash('error', "Categorie invalide");
        }
        return $this->redirectToRoute('admin_categories');
    }


}
