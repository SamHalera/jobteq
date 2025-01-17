<?php

namespace App\Service;

use App\Entity\Invitation;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{

    public function sendInvitationMail(MailerInterface $mailer, Invitation $invitation, User $currentUser): void
    {
        $email = (new TemplatedEmail())
            ->from($currentUser->getEmail())
            ->to($invitation->getEmail())
            ->subject('Invitation from Jobteq')
            ->htmlTemplate(('emails/invitation.html.twig'))
            ->context([
                'invitation' => [
                    'id' => $invitation->getId(),
                    'senderFirstname' => $currentUser->getFirstname(),
                    'senderLastname' => $currentUser->getLastname(),
                    'company' => $currentUser->getCompany()->getName(),
                    'url' => $_ENV["FRONT_URL"] . '/register?invitation=' . $invitation->getId()
                ]

            ]);

        $mailer->send($email);
    }
}
