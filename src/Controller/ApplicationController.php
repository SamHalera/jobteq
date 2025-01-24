<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\JobOffer;
use App\Entity\StatusApplicationEnum;
use App\Form\ApplicationType;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ApplicationController extends AbstractController
{
    #[Route('/application', name: 'app_application')]
    public function index(): Response
    {
        return $this->render('application/index.html.twig', [
            'controller_name' => 'ApplicationController',
        ]);
    }

    #[Route('/application/joboffer/{slug}', name: 'app_application_create')]
    public function apply(
        #[MapEntity(mapping: ['slug' => 'slug'])]
        JobOffer $jobOffer,
        EntityManagerInterface $em,
        UploaderService $uploader,
        Request $request
    ): Response {

        if (!$this->isGranted('IS_AUTHENTICATED')) {

            return $this->redirectToRoute('app_login');
        } elseif (!$this->isGranted('ROLE_CANDIDATE')) {
            $this->addFlash('danger', 'Only candidate can apply for a job offer');
            return $this->redirectToRoute('app_job_offer_public', [
                'slug' => $jobOffer->getSlug()
            ]);
        }

        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if (!$user) {
            //THROW EXCEPTION!
        }
        $application = new Application();
        $form = $this->createForm(ApplicationType::class, $application);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $resumeFile = $form->get('resumeFile')->getData();
            $publicFolder = $this->getParameter('kernel.project_dir') . '/public/uploads/application';
            $resumeName = $uploader->uploadFile($resumeFile, $publicFolder);
            $application
                ->setCandidate($user)
                ->setExaminer($jobOffer->getAuthor())
                ->setStatus(StatusApplicationEnum::PENDING)
                ->setResume($resumeName)
                ->setJobOffer($jobOffer)
            ;

            $em->persist($application);
            $em->flush();

            $this->addFlash('success', 'Your application has been sended to recruiter');
            return $this->redirectToRoute('app_job_offer_public', [
                'slug' => $jobOffer->getSlug()
            ]);
        }


        return $this->render('application/create.html.twig', [
            'user' => $user,
            'jobOffer' => $jobOffer,
            'form' => $form
        ]);
    }
}
