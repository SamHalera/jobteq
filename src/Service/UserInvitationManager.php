<?php

namespace App\Service;

use App\Entity\Invitation;
use App\Entity\InvitationStatusEnum;
use App\Entity\User;
use App\Repository\InvitationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class UserInvitationManager
{

    private InvitationRepository $invitationRepo;
    public function __construct(InvitationRepository $invitationRepo)
    {
        $this->invitationRepo = $invitationRepo;
    }

    public function updateInvitationFromRegister(EntityManagerInterface $em, int $invitationId, User $user): void
    {
        $invitation = $this->invitationRepo->find($invitationId);

        if (!$invitation) {
            throw new Exception('Not existing invitation');
        }

        $invitation->setStatus(InvitationStatusEnum::ACCEPTED);
        $user->setRoles(['ROLE_RECRUITER']);

        $em->persist($invitation);
        $em->persist($user);
        $em->flush();
    }
}
