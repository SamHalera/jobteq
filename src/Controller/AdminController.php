<?php

namespace App\Controller;

use App\Entity\LicenseConfig;
use App\Entity\SuperAdminJobConfig;

use App\Form\JobOffersConfigurationType;
use App\Form\LicenseConfigType;
use App\Repository\LicenseConfigRepository;
use App\Repository\LicenseRepository;
use App\Repository\SuperAdminJobConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/admin')]
class AdminController extends AbstractController
{


    const ADMIN_FOLDER_JOB_CONFIG = '/admin/configurations/job-offers-config/';
    const ADMIN_FOLDER_LICENSE_CONFIG = '/admin/configurations/license-config/';

    #[Route('/', name: 'app_admin_index')]
    public function index(EntityManagerInterface $em): Response
    {

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/configurations', name: 'app_admin_config_index')]
    public function indexConfigurations(SuperAdminJobConfigRepository $jobConfigRepo, LicenseConfigRepository $licenceConfigRepo): Response
    {

        $jobOffersConfigurations = $jobConfigRepo->findAll();
        $licensesConfigurations = $licenceConfigRepo->findAll();
        return $this->render('admin/configurations/index.html.twig', [
            'jobOffersConfigurations' => $jobOffersConfigurations,
            'licensesConfigurations' => $licensesConfigurations

        ]);
    }


    /////// JOB OFFERS CONFIGURATION
    #[Route('/job-offers-configuration/create', name: 'app_admin_config_create')]
    public function jobOffersConfigurationCreate(Request $request, EntityManagerInterface $em)
    {

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

        return $this->render(self::ADMIN_FOLDER_JOB_CONFIG . 'create.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/job-offers-configuration/{id}', name: 'app_admin_job_offers_config_index')]
    public function jobOffersConfiguration(SuperAdminJobConfig $jobConfig, EntityManagerInterface $em, Request $request): Response
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
    public function licenseConfigCreate(Request $request, EntityManagerInterface $em): Response
    {

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
}
