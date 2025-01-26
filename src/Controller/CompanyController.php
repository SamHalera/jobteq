<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\CompanyRegistrationType;
use App\Form\CompanyType;
use App\Repository\LicenseRepository;
use App\Service\UploaderService;
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
    public function index(Company $company, Request $request, EntityManagerInterface $em, UploaderService $uploader): Response
    {

        $this->denyAccessUnlessGranted("IS_AUTHENTICATED_FULLY");
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logoFile')->getData();
            $imageFile = $form->get('imageFile')->getData();
            $publicFolder = $this->getParameter('kernel.project_dir') . '/public/uploads/company';

            $logoName = $uploader($logoFile, $publicFolder);
            $imageName = $uploader($imageFile, $publicFolder);
            $olderLogo = $company->getLogo();
            $olderImage = $company->getImage();

            $company
                ->setLogo($logoName)
                ->setImage($imageName)
            ;


            $em->persist($company);
            $em->flush();

            if ($olderLogo) unlink($publicFolder . '/' . $olderLogo);
            if ($olderImage) unlink($publicFolder . '/' . $olderImage);

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
    public function userRecruiterRegister(Request $request, EntityManagerInterface $em, UploaderService $uploader): Response
    {

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $logoFile = $form->get('logoFile')->getData();
            $imageFile = $form->get('imageFile')->getData();
            $publicFolder = $this->getParameter('kernel.project_dir') . '/public/uploads/company';

            $logoName = $uploader($logoFile, $publicFolder);
            $imageName = $uploader($imageFile, $publicFolder);

            $company
                ->setLogo($logoName)
                ->setImage($imageName)
            ;


            $em->persist($company);
            $em->flush();
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
