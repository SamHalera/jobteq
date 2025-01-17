<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\License;
use App\Form\CompanyRegistrationType;
use App\Repository\LicenseRepository;
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

            $company->addUser($user);
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('app_company_manage_license', ['id' => $company->getId()]);
        }
        return $this->render('/company/company-register.html.twig', [
            'form' => $form
        ]);
    }


    #[ROute('/company/{id}/manage-license', name: 'app_company_manage_license')]
    public function payLicense(Company $company, LicenseRepository $licenseRepo): Response
    {

        $licenses = $licenseRepo->findBy(['status' => 'published']);
        return $this->render('company/manage-license.html.twig', [
            'company' => $company,
            'licenses' => $licenses
        ]);
    }
}
