<?php

namespace App\EventListener;

use App\Service\LoginAttemptService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class JWTAuthenticationSuccessListener implements EventSubscriberInterface
{
    public function __construct(
        private LoginAttemptService $loginAttemptService
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            Events::AUTHENTICATION_SUCCESS => 'onAuthenticationSuccessResponse',
        ];
    }

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $user = $event->getUser();
        $email = $user->getUserIdentifier();

        if ($email) {
            $this->loginAttemptService->recordSuccessfulAttempt($email);
        }
    }
}
