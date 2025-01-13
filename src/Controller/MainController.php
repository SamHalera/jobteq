<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(JobOfferRepository $jobRepo): Response
    {

        $jobOffers = $jobRepo->findJobOffersByStatus("published");

        return $this->render('main/index.html.twig', [
            'jobOffers' => $jobOffers
        ]);
    }
    #[Route('/job-offer/{slug}', name: 'app_job_offer_public', methods: ['GET'])]
    public function showJobOffer(
        #[MapEntity(mapping: ['slug' => 'slug'])]
        JobOffer $jobOffer
    ): Response {

        return $this->render('main/job-offer.html.twig', [
            'jobOffer' => $jobOffer
        ]);
    }
}
