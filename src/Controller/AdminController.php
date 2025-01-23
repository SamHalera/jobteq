<?php

namespace App\Controller;

use App\Entity\LicenseConfig;
use App\Entity\RoleConfig;
use App\Entity\SuperAdminJobConfig as EntitySuperAdminJobConfig;
use App\Form\JobOffersConfigurationType;
use App\Form\LicenseConfigType;
use App\Form\RoleConfigType;
use App\Repository\JobOfferRepository;
use App\Repository\LicenseConfigRepository;
use App\Repository\LicenseRepository;
use App\Repository\RoleConfigRepository;
use App\Repository\SuperAdminJobConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\SuperAdminJobConfig;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted("ROLE_SUPERADMIN")]
class AdminController extends AbstractController
{


    const ADMIN_FOLDER_JOB_CONFIG = '/admin/configurations/job-offers-config/';
    const ADMIN_FOLDER_LICENSE_CONFIG = '/admin/configurations/license-config/';
    const ADMIN_FOLDER_ROLE_CONFIG = '/admin/configurations/role-config/';

    #[Route('/', name: 'app_admin_index')]
    public function index(EntityManagerInterface $em): Response
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/configurations', name: 'app_admin_config_index')]
    public function indexConfigurations(
        SuperAdminJobConfigRepository $jobConfigRepo,
        LicenseConfigRepository $licenceConfigRepo,
        RoleConfigRepository $roleConfigRepo
    ): Response {

        $jobOffersConfigurations = $jobConfigRepo->findAll();
        $licensesConfigurations = $licenceConfigRepo->findAll();
        $rolesConfigurations = $roleConfigRepo->findAll();
        return $this->render('admin/configurations/index.html.twig', [
            'jobOffersConfigurations' => $jobOffersConfigurations,
            'licensesConfigurations' => $licensesConfigurations,
            'rolesConfigurations' => $rolesConfigurations

        ]);
    }


    /////// JOB OFFERS CONFIGURATION
    #[Route('/job-offers-configuration/create', name: 'app_admin_config_create')]
    public function jobOffersConfigurationCreate(Request $request, EntityManagerInterface $em, SuperAdminJobConfigRepository $jobConfigRepository)
    {
        $jobOffersConfigurations = $jobConfigRepository->findAll();

        if (count($jobOffersConfigurations) > 0) {

            $this->addFlash('danger', 'You can only have one configuration for job offers. Delete your current configuration in order to create a new one.');
            return $this->redirectToRoute('app_admin_config_index');
        }

        $jobOffersConfiguration = new SuperAdminJobConfig();
        $form = $this->createForm(
            JobOffersConfigurationType::class,
            $jobOffersConfiguration
        );

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($jobOffersConfiguration);
            $em->flush();
            return $this->redirectToRoute('app_admin_config_index');
        }

        return $this->render(self::ADMIN_FOLDER_JOB_CONFIG . 'create-config.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/job-offers-configuration/{id}', name: 'app_admin_job_offers_config_index')]
    public function jobOffersConfiguration(EntitySuperAdminJobConfig $jobConfig, EntityManagerInterface $em, Request $request): Response
    {


        $form = $this->createForm(
            JobOffersConfigurationType::class,
            $jobConfig
        );

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {



            $em->persist($jobConfig);
            $em->flush();
            return $this->redirectToRoute('app_admin_job_offers_config_index', ['id' => $jobConfig->getId()]);
        }
        return $this->render(self::ADMIN_FOLDER_JOB_CONFIG . 'index.html.twig', [
            'jobConfig' => $jobConfig,
            'form' => $form
        ]);
    }

    #[Route('/job-offers-configuration/delete/{id}', name: 'app_admin_config_delete')]
    public function jobOffersConfigurationDelete(SuperAdminJobConfig $jobConfig, EntityManagerInterface $em): Response
    {

        if (!$jobConfig) {
            $this->createNotFoundException();
        }
        $em->remove($jobConfig);
        $em->flush();


        return $this->redirectToRoute('app_admin_config_index');
    }


    /////// LICENCE CONFIGURATION

    #[Route('/license-configuration/create', name: 'app_admin_license_config_create')]
    public function licenseConfigCreate(Request $request, EntityManagerInterface $em, LicenseConfigRepository $licenceConfigRepo): Response
    {

        $licensesConfigurations = $licenceConfigRepo->findAll();
        if (count($licensesConfigurations) > 0) {

            $this->addFlash('danger', 'You can only have one license configuration. Delete your current configuration in order to create a new one.');
            return $this->redirectToRoute('app_admin_config_index');
        }
        $licenseConfiguration = new LicenseConfig();

        $form = $this->createForm(LicenseConfigType::class, $licenseConfiguration);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($licenseConfiguration);
            $em->flush();

            return $this->redirectToRoute('app_admin_config_index');
        }


        return $this->render(self::ADMIN_FOLDER_LICENSE_CONFIG . 'create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/license-configuration/{id}', name: 'app_admin_license_config_index')]
    public function licenseConfiguration(LicenseConfig $licenseConfig, EntityManagerInterface $em, Request $request): Response
    {


        $form = $this->createForm(
            LicenseConfigType::class,
            $licenseConfig
        );

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($licenseConfig);
            $em->flush();
            return $this->redirectToRoute('app_admin_license_config_index', ['id' => $licenseConfig->getId()]);
        }
        return $this->render(self::ADMIN_FOLDER_LICENSE_CONFIG . 'index.html.twig', [
            'licenseConfig' => $licenseConfig,
            'form' => $form
        ]);
    }

    #[Route('/license-configuration/delete/{id}', name: 'app_admin_license_config_delete')]
    public function licenseConfigurationDelete(LicenseConfig $licenseConfig, EntityManagerInterface $em,): Response
    {

        if (!$licenseConfig) {
            $this->createNotFoundException();
        }
        $em->remove($licenseConfig);
        $em->flush();


        return $this->redirectToRoute('app_admin_config_index');
    }
    /////// ROLES CONFIGURATION

    #[Route('/role-configuration/create', name: 'app_admin_role_config_create')]
    public function roleConfigCreate(Request $request, EntityManagerInterface $em, RoleConfigRepository $roleConfigRepo): Response
    {

        $roleConfigurations = $roleConfigRepo->findAll();
        if (count($roleConfigurations) > 0) {

            $this->addFlash('danger', 'You can only have one roles configuration. Delete your current configuration in order to create a new one.');
            return $this->redirectToRoute('app_admin_config_index');
        }

        $roleConfiguration = new RoleConfig();

        $form = $this->createForm(RoleConfigType::class, $roleConfiguration);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($roleConfiguration);
            $em->flush();

            return $this->redirectToRoute('app_admin_config_index');
        }


        return $this->render(self::ADMIN_FOLDER_ROLE_CONFIG . 'create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/role-configuration/{id}', name: 'app_admin_role_config_index')]
    public function roleConfiguration(RoleConfig $roleConfig, EntityManagerInterface $em, Request $request): Response
    {


        $form = $this->createForm(
            RoleConfigType::class,
            $roleConfig
        );

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $em->persist($roleConfig);
            $em->flush();
            return $this->redirectToRoute('app_admin_role_config_index', ['id' => $roleConfig->getId()]);
        }
        return $this->render(self::ADMIN_FOLDER_ROLE_CONFIG . 'index.html.twig', [
            'roleConfig' => $roleConfig,
            'form' => $form
        ]);
    }

    #[Route('/role-configuration/delete/{id}', name: 'app_admin_role_config_delete')]
    public function roleConfigurationDelete(RoleConfig $roleConfig, EntityManagerInterface $em,): Response
    {

        if (!$roleConfig) {
            $this->createNotFoundException();
        }
        $em->remove($roleConfig);
        $em->flush();


        return $this->redirectToRoute('app_admin_config_index');
    }
}
