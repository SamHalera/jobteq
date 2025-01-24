<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\StatusApplicationEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_RECRUITER')]
class RecruiterController extends AbstractController
{
    #[Route('/recruiter', name: 'app_recruiter')]
    public function index(): Response
    {
        return $this->render('recruiter/index.html.twig', [
            'controller_name' => 'RecruiterController',
        ]);
    }

    #[Route('/recruiter/application/action/{id}/{status}', name: 'app_recruiter_application_action')]
    public function recruiterApplicationUnderStudy(Application $application, string $status, EntityManagerInterface $em): Response
    {
        $statusApplication = StatusApplicationEnum::PENDING;

        switch ($status) {
            case 'under study':
                $statusApplication = StatusApplicationEnum::UNDER_STUDY;
                break;
            case 'accepted':
                $statusApplication = StatusApplicationEnum::ACCEPTED;
                break;
            case 'rejected':
                $statusApplication = StatusApplicationEnum::REJECTED;
                break;
        }
        $application->setStatus($statusApplication);

        $em->persist($application);
        $em->flush();

        $status = $application->getStatusString();

        $this->addFlash('success', "The application has changed status. It has been marked as $status");
        return $this->redirectToRoute('app_user_dashboard');
    }
    #[Route('/recruiter/application/{id}', name: 'app_recruiter_application')]
    public function recruiterApplication(Application $application): Response
    {

        $statusApplication =  ['under study', 'pending', 'accepted', 'rejected'];
        return $this->render('recruiter/application.html.twig', [
            'application' => $application,
            'statusApplication' => $statusApplication
        ]);
    }
}
