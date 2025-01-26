<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Repository\CompanyRepository;
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


    ////JOB OFERS
    #[Route('/job-offer/{slug}', name: 'app_job_offer_public', methods: ['GET'])]
    public function showJobOffer(
        #[MapEntity(mapping: ['slug' => 'slug'])]
        JobOffer $jobOffer
    ): Response {

        /**
         * @var User $user
         */
        $user = $this->getUser();

        /**
         * @var Application $application
         */
        $application = null;

        if ($user && $user->getCandidate()) {

            foreach ($user->getCandidate()->getApplications() as $value) {


                if ($value->getJobOffer()->getId() === $jobOffer->getId()) {

                    $application = $value;
                }
            }
        }
        return $this->render('main/job-offer.html.twig', [
            'jobOffer' => $jobOffer,
            'application' => $application
        ]);
    }


    ////COMPANY
    #[Route('/companies', name: 'app_company_index')]
    public function allCompanies(CompanyRepository $companyRepo): Response
    {
        $companies = $companyRepo->findAll(["isEnable" => true]);

        return $this->render('main/company/index.html.twig', [
            'companies' => $companies
        ]);
    }
    #[Route('/company/{id}', name: 'app_company_show')]
    public function companyShow(Company $company): Response
    {

        if (!$company) {
            return $this->createNotFoundException();
        }


        return $this->render('main/company/show.html.twig', [
            'company' => $company
        ]);
    }
}
