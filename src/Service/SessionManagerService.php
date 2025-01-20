<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionManagerService
{

    public function __construct(private readonly RequestStack $requestStack) {}

    public function createSession(string $sessionKey, $value): void
    {
        $session = $this->requestStack->getSession();
        $session->set($sessionKey, true);
    }
    public function getInvitationIsAccepted(string $sessionKey): bool
    {

        $session = $this->requestStack->getSession();
        $session->get($sessionKey);
        return $session->get($sessionKey) ? $session->get($sessionKey) : false;
    }

    public function clearInvitationIsAccepted($sessionKey): void
    {
        $session = $this->requestStack->getSession();
        $session->remove($sessionKey);
        // $session->clear();
    }
}
