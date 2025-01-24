<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Entity\InvitationStatusEnum;
use App\Entity\User;
use App\Form\UserProfileType;
use App\Repository\ApplicationRepository;
use App\Service\SessionManagerService;
use App\Service\UploaderService;
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
    public function index(ApplicationRepository $applicationRepo): Response
    {

        // $user->setRoles(["ROLE_RECRUITER"]);

        /**
         * @var \App\Entity\User $user
         */
        $user = $this->getUser();
        $optionsData = [
            'user' => $user
        ];



        if ($this->isGranted('ROLE_CANDIDATE')) {
            $optionsData = [
                'candidate' => $user->getCandidate()
            ];
        } elseif ($this->isGranted('ROLE_RECRUITER')) {

            $applications = $applicationRepo->findBy([
                'examiner' => $user->getId()
            ]);

            $optionsData['applications'] = $applications;
        }


        return $this->render('user/dashboard.html.twig', $optionsData);
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
    }

    #[Route('/profile', name: 'app_user_profile')]
    public function profile(EntityManagerInterface $em, Request $request, UploaderService $uploader): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $file = $form->get('thumbnailFile')->getData();
            $publicFolder = $this->getParameter('kernel.project_dir') . '/public/uploads/user';

            $fileName = $uploader->uploadFile($file, $publicFolder);

            $olderThumbnail = null;
            if ($user instanceof User) {
                $olderThumbnail = $user->getThumbnail();
                $user->setThumbnail($fileName);
            }

            $em->persist($user);
            $em->flush();
            if ($olderThumbnail) {
                unlink($publicFolder . '/' . $olderThumbnail);
            }

            $this->addFlash('success', 'Your profile has been updated');

            return $this->redirectToRoute('app_user_profile', [
                'user' => $user,
                'form' => $form
            ]);
        }
        return $this->render('user/profile/index.html.twig', [
            'user' => $user,
            'form' => $form
        ]);
    }
}
