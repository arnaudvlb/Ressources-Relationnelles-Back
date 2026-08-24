<?php

namespace App\Service;

use App\Entity\Utilisateurs;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Singleton pour gérer la session utilisateur connecté
 * Pattern Singleton : une seule instance par requête
 */
class UserSessionManager
{
    private static ?self $instance = null;
    private ?Utilisateurs $currentUser = null;
    private array $sessionData = [];

    private function __construct(private Security $security)
    {
        $this->initializeSession();
    }

    /**
     * Obtient l'instance unique du manager de session
     */
    public static function getInstance(Security $security): self
    {
        if (self::$instance === null) {
            self::$instance = new self($security);
        }

        return self::$instance;
    }

    /**
     * Initialise la session avec l'utilisateur connecté
     */
    private function initializeSession(): void
    {
        $user = $this->security->getUser();

        if ($user instanceof Utilisateurs) {
            $this->currentUser = $user;
            $this->sessionData = $this->buildSessionData($user);
        }
    }

    /**
     * Retourne l'utilisateur actuellement connecté
     */
    public function getCurrentUser(): ?Utilisateurs
    {
        return $this->currentUser;
    }

    /**
     * Vérifie si un utilisateur est connecté
     */
    public function isAuthenticated(): bool
    {
        return $this->currentUser !== null;
    }

    /**
     * Retourne l'ID de l'utilisateur connecté
     */
    public function getUserId(): ?int
    {
        return $this->currentUser?->getId();
    }

    /**
     * Retourne l'email de l'utilisateur connecté
     */
    public function getUserEmail(): ?string
    {
        return $this->currentUser?->getEmail();
    }

    /**
     * Retourne les rôles de l'utilisateur connecté
     */
    public function getUserRoles(): array
    {
        return $this->currentUser?->getRoles() ?? [];
    }

    /**
     * Retourne les données de session
     */
    public function getSessionData(): array
    {
        return $this->sessionData;
    }

    /**
     * Ajoute une données à la session
     */
    public function setSessionData(string $key, mixed $value): self
    {
        $this->sessionData[$key] = $value;
        return $this;
    }

    /**
     * Récupère une donnée de la session
     */
    public function getSessionValue(string $key, mixed $default = null): mixed
    {
        return $this->sessionData[$key] ?? $default;
    }

    /**
     * Met à jour l'utilisateur de la session
     */
    public function setCurrentUser(Utilisateurs $user): self
    {
        $this->currentUser = $user;
        $this->sessionData = $this->buildSessionData($user);
        return $this;
    }

    /**
     * Construit les données de session pour un utilisateur
     */
    private function buildSessionData(Utilisateurs $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'pseudo' => $user->getPseudo(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'roles' => $user->getRoles(),
            'connected_at' => new \DateTime(),
            'is_admin' => in_array('ROLE_ADMIN', $user->getRoles()),
        ];
    }

    /**
     * Vide la session (déconnexion)
     */
    public function clearSession(): void
    {
        $this->currentUser = null;
        $this->sessionData = [];
    }

    /**
     * Vérifie si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->getUserRoles());
    }

    /**
     * Vérifie si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('ROLE_ADMIN');
    }

    /**
     * Empêche le clonage
     */
    private function __clone() {}

    /**
     * Empêche la sérialisation
     */
    public function __serialize(): array
    {
        throw new \Exception("Cannot serialize a Singleton");
    }

    /**
     * Empêche la désérialisation
     */
    public function __unserialize(array $data): void
    {
        throw new \Exception("Cannot unserialize a Singleton");
    }
}
