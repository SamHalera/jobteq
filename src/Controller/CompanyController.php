<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyRegistrationType;
use App\Form\CompanyType;
use App\Repository\LicenseRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_MANAGER')]
#[Route('/manager')]
class CompanyController extends AbstractController
{
    #[Route('/company/{id}', name: 'app_company')]
    public function index(Company $company, Request $request, EntityManagerInterface $em): Response
    {

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        $logoFile = $form->get('logoFile')->getData();

        if ($form->isSubmitted() && $form->isValid()) {



            $em->persist($company);
            $em->flush();

            $this->addFlash('success', 'Your company informations have been updated!');
            return $this->redirectToRoute('app_company', [
                'id' => $company->getId(),
                'company' => $company,
                'form' => $form
            ]);
        }

        return $this->render('company/index.html.twig', [
            'company' => $company,
            'form' => $form
        ]);
    }
    #[Route('/company/register/', name: 'app_company_register')]
    public function userRecruiterRegister(Request $request, EntityManagerInterface $em): Response
    {

        $company = new Company();
        $form = $this->createForm(CompanyRegistrationType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $this->getUser();
            if ($user instanceof User) {
                $user->setCompany($company);
                $em->persist($user);
            }


            $company->addUser($user);
            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute('app_company_manage_license', ['id' => $company->getId()]);
        }
        return $this->render('/company/company-register.html.twig', [
            'form' => $form
        ]);
    }


    #[Route('/company/manage-license/{id<\d+>}', name: 'app_company_manage_license')]
    public function payLicense(Company $company, LicenseRepository $licenseRepo): Response
    {

        $licenses = $licenseRepo->findBy(['status' => 'published']);
        return $this->render('company/manage-license.html.twig', [
            'company' => $company,
            'licenses' => $licenses
        ]);
    }
    #[Route('/company/accept-license/{id<\d+>}/{licenseId}', name: 'app_company_accept_license')]
    public function acceptLicense(Company $company,  $licenseId, LicenseRepository $licenseRepo, EntityManagerInterface $em): Response
    {

        if (!$company) {
            $this->createNotFoundException();
        }

        $license = $licenseRepo->find($licenseId);

        if (!$license) {
            $this->createNotFoundException();
        }

        $company
            ->setLicense($license)
            ->setEnabled(true);

        $em->flush();

        return $this->redirectToRoute('app_user_dashboard');
    }
}
