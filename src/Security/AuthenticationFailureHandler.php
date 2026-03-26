<?php

namespace App\Security;

use App\Service\LoginAttemptService;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(
        private LoginAttemptService $loginAttemptService,
        private RequestStack $requestStack
    ) {}

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;

        if ($email) {
            // Enregistrer la tentative échouée
            $this->loginAttemptService->recordFailedAttempt($email);

            // Vérifier si le compte est verrouillé
            if ($this->loginAttemptService->isLocked($email)) {
                $remainingTime = $this->loginAttemptService->getRemainingLockTime($email);
                return new JsonResponse([
                    'message' => 'Compte temporairement verrouillé suite à plusieurs tentatives échouées',
                    'error' => 'account_locked',
                    'retry_after' => $remainingTime
                ], 429); // 429 = Too Many Requests
            }

            $attempts = $this->loginAttemptService->getAttemptsCount($email);
            return new JsonResponse([
                'message' => 'Identifiant ou mot de passe incorrect',
                'error' => 'invalid_credentials',
                'attempts' => $attempts,
                'max_attempts' => 3
            ], 401);
        }

        return new JsonResponse([
            'message' => 'Identifiant ou mot de passe incorrect',
            'error' => 'invalid_credentials'
        ], 401);
    }
}
