<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Invitation;
use App\Entity\InvitationStatusEnum;
use App\Entity\User;
use App\Form\InvitationType;
use App\Repository\InvitationRepository;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

#[IsGranted('ROLE_MANAGER')]
class ManagerController extends AbstractController
{
    #[Route('/invitations', name: 'app_manager_invitation_index')]
    public function index(InvitationRepository $invitationRepo): Response
    {
        $user = $this->getUser();
        if (!$user) {
            $this->createNotFoundException();
        }
        $id = null;
        if ($user instanceof User) {
            $id = $user->getId();
        }
        $invitations = $invitationRepo->findInvitationsByCreatedBy($id);

        return $this->render('manager/index.html.twig', [
            'invitations' => $invitations,
        ]);
    }


    #[Route('/invitations/new', name: 'app_manager_invitation_create')]
    public function invitationCreate(Request $request, EntityManagerInterface $em, MailerService $mailerService, MailerInterface $mailer): Response
    {
        $currentUser = $this->getUser();

        $invitation = new Invitation();
        $form = $this->createForm(InvitationType::class, $invitation);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $company = null;
            if ($currentUser instanceof User) {

                $company = $currentUser->getCompany();
            }
            $uid = Uuid::v7();

            $invitation
                ->setToken($uid)
                ->setCompany($company)
                ->setCreatedBy($currentUser)
                ->setStatus(InvitationStatusEnum::PENDING)
                ->setSendings(1)
            ;
            $em->persist($invitation);
            $em->flush();

            $mailerService->sendInvitationMail($mailer, $invitation, $currentUser);


            return $this->redirectToRoute('app_manager_invitation_index');
        }

        return $this->render('manager/new-invitation.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/invitations/delete/{id}', name: 'app_manager_invitation_delete')]
    public function InvitationDelete(Invitation $invitation, EntityManagerInterface $em): Response
    {

        if (!$invitation) {
            $this->createNotFoundException();
        }
        $em->remove($invitation);
        $em->flush();


        return $this->redirectToRoute('app_manager_invitation_index');
    }
    #[Route('/invitations/edit/{id}', name: 'app_manager_invitation_edit')]
    public function invitationEdit(Invitation $invitation, EntityManagerInterface $em, Request $request): Response
    {

        $form = $this->createForm(InvitationType::class, $invitation);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $uid = Uuid::v7();
            $invitation
                ->setToken($uid);

            $em->persist($invitation);
            $em->flush();

            return $this->redirectToRoute('app_manager_invitation_index');
        }

        return $this->render('manager/edit-invitation.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/invitations/re-send-email/{id}', name: 'app_manager_invitation_resend_email')]
    public function invitationResendEmail(Invitation $invitation, EntityManagerInterface $em,  MailerService $mailerService, MailerInterface $mailer): Response
    {



        $currentUser = $this->getUser();
        $mailerService->sendInvitationMail($mailer, $invitation, $currentUser);

        $sendings = $invitation->getSendings() + 1;
        $invitation->setSendings($sendings);
        $em->persist($invitation);
        $em->flush();

        return $this->redirectToRoute('app_manager_invitation_index');
    }
}
