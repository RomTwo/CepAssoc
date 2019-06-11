<?php

namespace App\Controller\administration;

use App\Entity\Category;
use App\Form\AdminCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminActivitiesCategoryController extends AbstractController
{

    /**
     * Return all the categories
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Update the category having $id as ID
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit($id, Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $category = $entityManager->getRepository(Category::class)->find($id);

        // if the category exists
        if($category) {
            $form = $this->createForm(AdminCategoryType::class, $category);
            $form->handleRequest($request);

            // if the form is valid
            if ($form->isSubmitted() && $form->isValid()) {
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
        else{
            $this->addFlash('error', "La catégorie n'existe pas");
            return $this->redirectToRoute('admin_categories');
        }

    }

    /**
     * Delete a category thanks to its id
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id)
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
