<?php

namespace App\Security\Voter;

use App\Entity\Favoris;
use App\Entity\Utilisateurs;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FavorisVoter extends Voter
{
    public const EDIT = 'FAVORI_EDIT';
    public const DELETE = 'FAVORI_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Favoris;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Utilisateurs) {
            return false;
        }

        /** @var Favoris $favori */
        $favori = $subject;

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($favori, $user);
            case self::DELETE:
                return $this->canDelete($favori, $user);
            default:
                return false;
        }
    }

    private function canEdit(Favoris $favori, Utilisateurs $user): bool
    {
        return $favori->getUtilisateur() === $user;
    }

    private function canDelete(Favoris $favori, Utilisateurs $user): bool
    {
        return $favori->getUtilisateur() === $user;
    }
}
