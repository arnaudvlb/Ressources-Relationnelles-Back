<?php

namespace App\Security;

use App\Entity\Utilisateurs;
use App\Service\LoginAttemptService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private LoginAttemptService $loginAttemptService,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): JsonResponse
    {
        $email = $token->getUserIdentifier();
        $user = $token->getUser();

        if ($email) {
            $this->loginAttemptService->recordSuccessfulAttempt($email);
        }

        $jwt = $this->jwtManager->create($token->getUser());

        $userData = null;
        if ($user instanceof Utilisateurs) {
            $userData = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'pseudo' => $user->getPseudo(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'telephone' => $user->getTelephone(),
                'roles' => $user->getRoles(),
            ];
        }

        return new JsonResponse([
            'data' => [
                'token' => $jwt,
                'user' => $userData,
            ]
        ]);
    }
}
