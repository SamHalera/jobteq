<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\CandidateType;
use App\Repository\CandidateRepository;
use App\Service\UploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/candidate')]
final class CandidateController extends AbstractController
{
    #[Route(name: 'app_candidate_index', methods: ['GET'])]
    public function index(CandidateRepository $candidateRepository): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();

        if (!$user->getCandidate()) {
            return $this->redirectToRoute('app_user_dashboard');
        }
        return $this->render('candidate/index.html.twig', [
            'user' => $user,
        ]);
    }


    #[Route('/new', name: 'app_candidate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UploaderService $uploader): Response
    {
        /**
         * @var \App\Entity\User $user
         */
        $user = $this->getUser();
        if ($user->getCandidate()) {
            $this->addFlash('danger', 'You already have a candidate profile. You can edit it.');
            return $this->redirectToRoute('app_user_dashboard');
        }


        $candidate = new Candidate();


        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $resumeFile = $form->get('resumeFile')->getData();
            $publicFolder = $this->getParameter('kernel.project_dir') . '/public/uploads/candidate';
            $resumeName = $uploader->uploadFile($resumeFile, $publicFolder);


            $candidate
                ->setResume($resumeName)
                ->setUser($user);

            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->redirectToRoute('app_candidate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('candidate/new.html.twig', [
            'candidate' => $candidate,
            'form' => $form,
        ]);
    }

    #[Route('/resume/candidate/{id}', name: 'app_resume_pdf', methods: ['GET'])]
    public function show(Candidate $candidate): Response
    {
        $resume = $candidate->getResume();
        $pathToResume = "/uploads/candidate/$resume";
        return $this->redirect($pathToResume);
    }

    #[Route('/{id}/edit', name: 'app_candidate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Candidate $candidate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_candidate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('candidate/edit.html.twig', [
            'candidate' => $candidate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_candidate_delete', methods: ['POST'])]
    public function delete(Request $request, Candidate $candidate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $candidate->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($candidate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_candidate_index', [], Response::HTTP_SEE_OTHER);
    }
}
