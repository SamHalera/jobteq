<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyRegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanyController extends AbstractController
{
    #[Route('/company', name: 'app_company')]
    public function index(): Response
    {
        return $this->render('company/index.html.twig', [
            'controller_name' => 'CompanyController',
        ]);
    }
    #[Route('/company/register/', name: 'app_company_register')]
    public function userRecruiterRegister(Request $request, EntityManagerInterface $em): Response
    {

        $user = $this->getUser();
        $company = new Company();
        $form = $this->createForm(CompanyRegistrationType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // $em->persist($user);
            // $em->flush();

            return $this->redirectToRoute('app_user_dashboard');
        }
        return $this->render('/company/company-register.html.twig', [
            'form' => $form
        ]);
    }
}
