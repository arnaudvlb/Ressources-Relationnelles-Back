<?php

namespace App\Security\Voter;

use App\Entity\Ressources;
use App\Entity\Utilisateurs;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RessourcesVoter extends Voter
{
    public const EDIT = 'RESSOURCE_EDIT';
    public const DELETE = 'RESSOURCE_DELETE';
    public const VIEW = 'RESSOURCE_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])
            && $subject instanceof Ressources;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Utilisateurs) {
            return false;
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
        if ($ressource->getVisibilite()->value === 'public') {
            return true;
        }

        if ($ressource->getUtilisateur() === $user) {
            return true;
        }

        if ($ressource->getVisibilite()->value === 'friend') {
            return true;
        }

        return false;
    }

    private function canEdit(Ressources $ressource, Utilisateurs $user): bool
    {
        return $ressource->getUtilisateur() === $user;
    }

    private function canDelete(Ressources $ressource, Utilisateurs $user): bool
    {
        return $ressource->getUtilisateur() === $user;
    }
}
