<?php

namespace App\Controller;

use App\Form\JobOfferType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job_offer/admin')]
class JobOfferAdminController extends AbstractController
{
    #[Route('/', name: 'app_job_offer_admin_index')]
    public function index(): Response
    {
        return $this->render('job_offer_admin/index.html.twig', [
            'controller_name' => 'JobOfferAdminController',
        ]);
    }
    #[Route('/new', name: 'app_joboffer_create', methods: ['POST', 'GET'])]
    public function create(Request $request): Response
    {
        $form = $this->createForm(JobOfferType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            dd($form);
        }

        return $this->render('job_offer_admin/new.html.twig', [
            'form' => $form
        ]);
    }
}
