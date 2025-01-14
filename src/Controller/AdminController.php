<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\JobOfferType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authenticator\FormLoginAuthenticator;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin_index')]
    public function index(EntityManagerInterface $em): Response
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/category', name: 'app_admin_category_index')]
    public function categories(CategoryRepository $categoryRepo): Response
    {

        $categories = $categoryRepo->findAll();
        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/category/new', name: 'app_admin_category_create')]
    public function createCategory(EntityManagerInterface $em, Request $request): Response
    {

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'A new category has been created!');
            return $this->redirectToRoute('app_admin_category_index');
        }

        return $this->render('admin/category/new-category.html.twig', [
            'form' => $form,
        ]);
    }
}
