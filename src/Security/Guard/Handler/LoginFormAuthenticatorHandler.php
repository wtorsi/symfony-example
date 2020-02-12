<?php declare(strict_types=1);

namespace Security\Guard\Handler;

use Security\Guard\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use User\Entity\User;

class LoginFormAuthenticatorHandler
{
    private GuardAuthenticatorHandler $handler;
    private LoginFormAuthenticator $authenticator;
    private string $providerKey;

    public function __construct(GuardAuthenticatorHandler $handler, LoginFormAuthenticator $authenticator, string $providerKey = 'main')
    {
        $this->handler = $handler;
        $this->authenticator = $authenticator;
        $this->providerKey = $providerKey;
    }

    public function authenticate(User $user, Request $request): Response
    {
        return $this->handler->authenticateUserAndHandleSuccess($user, $request, $this->authenticator, $this->providerKey);
    }
}