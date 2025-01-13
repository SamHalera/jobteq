<?php

namespace App\Controller;

use App\Entity\JobOffer;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
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
