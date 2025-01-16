<?php

namespace App\Controller;


use App\Entity\SuperAdminJobConfig;

use App\Form\JobOffersConfigurationType;

use App\Repository\SuperAdminJobConfigRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


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
    #[Route('/configurations', name: 'app_admin_config_index')]
    public function indexConfigurations(SuperAdminJobConfigRepository $jobConfigRepo): Response
    {

        $jobOffersConfigurations = $jobConfigRepo->findAll();
        return $this->render('admin/configurations/index.html.twig', [
            'jobOffersConfigurations' => $jobOffersConfigurations,

        ]);
    }


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

        $template = '/admin/configurations/job-offers-config/create.html.twig';
        return $this->render($template, [
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
            return $this->redirectToRoute('app_admin_job_offers_config_index');
        }
        return $this->render('admin/configurations/job-offers-config/index.html.twig', [
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
}
