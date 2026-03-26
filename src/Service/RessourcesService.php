<?php

namespace App\Service;

use App\Entity\Ressources;
use App\Repository\RessourcesRepository;

/**
 * Service pour les opérations sur les ressources avec gestion de session
 * Démontre l'utilisation du Singleton UserSessionManager
 */
class RessourcesService
{
    public function __construct(
        private RessourcesRepository $ressourcesRepository,
        private UserSessionManager $sessionManager,
    ) {}

    /**
     * Récupère les ressources de l'utilisateur connecté
     */
    public function getUserRessources(): array
    {
        if (!$this->sessionManager->isAuthenticated()) {
            throw new \Exception("Utilisateur non connecté");
        }

        $user = $this->sessionManager->getCurrentUser();
        if (!$user) {
            return [];
        }

        return $this->ressourcesRepository->findBy(['utilisateur' => $user]);
    }

    /**
     * Crée une ressource pour l'utilisateur connecté
     */
    public function createRessource(string $titre, string $contenu): Ressources
    {
        if (!$this->sessionManager->isAuthenticated()) {
            throw new \Exception("Utilisateur non connecté");
        }

        $user = $this->sessionManager->getCurrentUser();

        $ressource = new Ressources();
        $ressource->setTitre($titre);
        $ressource->setContenu($contenu);
        $ressource->setUtilisateur($user);
        $ressource->setDateCreation(new \DateTimeImmutable());
        $ressource->setEstVisible(true);

        return $ressource;
    }

    /**
     * Supprime une ressource si l'utilisateur est le propriétaire
     */
    public function deleteRessource(Ressources $ressource): bool
    {
        if (!$this->sessionManager->isAuthenticated()) {
            throw new \Exception("Utilisateur non connecté");
        }

        $currentUser = $this->sessionManager->getCurrentUser();

        // Vérifier que l'utilisateur est propriétaire ou admin
        if ($ressource->getUtilisateur() !== $currentUser && !$this->sessionManager->isAdmin()) {
            throw new \Exception("Vous n'avez pas la permission de supprimer cette ressource");
        }

        return true;
    }

    /**
     * Obtient les statistiques de l'utilisateur connecté
     */
    public function getUserStats(): array
    {
        if (!$this->sessionManager->isAuthenticated()) {
            throw new \Exception("Utilisateur non connecté");
        }

        $user = $this->sessionManager->getCurrentUser();
        $ressources = $this->ressourcesRepository->findBy(['utilisateur' => $user]);

        return [
            'user_id' => $this->sessionManager->getUserId(),
            'user_email' => $this->sessionManager->getUserEmail(),
            'total_ressources' => count($ressources),
            'is_admin' => $this->sessionManager->isAdmin(),
            'session_data' => $this->sessionManager->getSessionData(),
        ];
    }
}
