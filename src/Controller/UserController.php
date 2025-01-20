<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\InvitationStatusEnum;
use App\Entity\User;
use App\Service\SessionManagerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]

class UserController extends AbstractController
{
    #[Route('/dashboard', name: 'app_user_dashboard')]
    public function index(): Response
    {

        // $user->setRoles(["ROLE_RECRUITER"]);

        return $this->render('user/dashboard.html.twig');
    }
    #[Route('/add-candidate-role/{id}', name: 'app_user_candidate_register')]
    public function addCandidateRole(User $user, EntityManagerInterface $em): Response
    {


        if (!$user) {
            $this->createNotFoundException();
        }
        $user->setRoles(["ROLE_CANDIDATE"]);

        // $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_login');
    }

    #[Route('/manage-invitation/{id}', name: 'app_invitation_user_answer')]
    public function invitationManage(Invitation $invitation): Response
    {

        return $this->render('user/answer-to-invitation.html.twig', [
            'invitation' => $invitation
        ]);
    }
    #[Route('/decline-invitation/{id}', name: 'app_invitation_user_decline')]
    public function declineInvitation(Invitation $invitation, EntityManagerInterface $em, SessionManagerService $sessionManagerService): Response
    {

        if (!$invitation) {
            $this->createNotFoundException();
        }


        $sessionManagerService->clearInvitationIsAccepted('invitationIsAccepted');
        $invitation->setStatus(InvitationStatusEnum::DECLINED);
        $em->persist($invitation);
        $em->flush();

        return $this->render('user/decline-feedback.html.twig', [
            'invitation' => $invitation
        ]);

        // return $this->redirectToRoute('app_manager_invitation_index');
    }
}
