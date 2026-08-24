<?php

namespace App\Controller;

use App\Service\UserSessionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
#[IsGranted('ROLE_USER')]
class SessionController extends AbstractController
{
    /**
     * Retourne les informations de la session utilisateur
     */
    #[Route('/session/info', name: 'session_info', methods: ['GET'])]
    public function getSessionInfo(UserSessionManager $sessionManager): JsonResponse
    {
        if (!$sessionManager->isAuthenticated()) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 401);
        }

        return new JsonResponse([
            'success' => true,
            'user' => [
                'id' => $sessionManager->getUserId(),
                'email' => $sessionManager->getUserEmail(),
                'roles' => $sessionManager->getUserRoles(),
                'is_admin' => $sessionManager->isAdmin(),
            ],
            'session_data' => $sessionManager->getSessionData(),
        ]);
    }

    /**
     * Retourne le profil complet de l'utilisateur connecté
     */
    #[Route('/session/profile', name: 'session_profile', methods: ['GET'])]
    public function getUserProfile(UserSessionManager $sessionManager): JsonResponse
    {
        $user = $sessionManager->getCurrentUser();

        if (!$user) {
            return new JsonResponse(['message' => 'Utilisateur non connecté'], 401);
        }

        return new JsonResponse([
            'success' => true,
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'pseudo' => $user->getPseudo(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'telephone' => $user->getTelephone(),
            'photoProfil' => $user->getPhotoProfil(),
            'statusCompte' => $user->isStatusCompte(),
            'role' => $user->getRole()?->getLibelle(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * Retourne le statut de la session
     */
    #[Route('/session/status', name: 'session_status', methods: ['GET'])]
    public function getSessionStatus(UserSessionManager $sessionManager): JsonResponse
    {
        return new JsonResponse([
            'success' => true,
            'authenticated' => $sessionManager->isAuthenticated(),
            'user_id' => $sessionManager->getUserId(),
            'user_email' => $sessionManager->getUserEmail(),
            'is_admin' => $sessionManager->isAdmin(),
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);
    }
}
