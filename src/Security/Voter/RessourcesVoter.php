<?php

namespace App\Security\Voter;

use App\Entity\Ressources;
use App\Entity\Utilisateurs;
use App\Repository\AmisRepository;
use App\Repository\UtilisateursRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RessourcesVoter extends Voter
{
    public const EDIT = 'RESSOURCE_EDIT';
    public const DELETE = 'RESSOURCE_DELETE';
    public const VIEW = 'RESSOURCE_VIEW';

    public function __construct(private AmisRepository $amisRepository, private UtilisateursRepository $utilisateursRepository) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])
            && $subject instanceof Ressources;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // Ensure we have an instance of Utilisateurs. JWT or other providers may give a string or different UserInterface.
        if (!$user instanceof Utilisateurs) {
            if (is_string($user)) {
                $user = $this->utilisateursRepository->findOneBy(['email' => $user]);
            } elseif ($user instanceof UserInterface) {
                $user = $this->utilisateursRepository->findOneBy(['email' => $user->getUserIdentifier()]);
            } else {
                return false;
            }

            if (!$user instanceof Utilisateurs) {
                return false;
            }
        }

        /** @var Ressources $ressource */
        $ressource = $subject;

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($ressource, $user);
            case self::EDIT:
                return $this->canEdit($ressource, $user);
            case self::DELETE:
                return $this->canDelete($ressource, $user);
            default:
                return false;
        }
    }

    private function canView(Ressources $ressource, Utilisateurs $user): bool
    {
        // Public resources are viewable by anyone authenticated (handled before)
        if ($ressource->getVisibilite()->value === 'public') {
            return true;
        }

        // Owner can always view (cast IDs to int to avoid type mismatch)
        $owner = $ressource->getUtilisateur();
        if ($owner !== null && (int) $owner->getId() === (int) $user->getId()) {
            return true;
        }

        // Friend visibility: only accepted friends can view
        if ($ressource->getVisibilite()->value === 'friend' && $owner !== null) {
            $relation = $this->amisRepository->relationExiste((int) $owner->getId(), (int) $user->getId());
            return $relation !== null && $relation->getStatut() === 'accepte';
        }

        return false;
    }

    private function canEdit(Ressources $ressource, Utilisateurs $user): bool
    {
        $owner = $ressource->getUtilisateur();
        return $owner !== null && (int) $owner->getId() === (int) $user->getId();
    }

    private function canDelete(Ressources $ressource, Utilisateurs $user): bool
    {
        $owner = $ressource->getUtilisateur();
        return $owner !== null && (int) $owner->getId() === (int) $user->getId();
    }
}
