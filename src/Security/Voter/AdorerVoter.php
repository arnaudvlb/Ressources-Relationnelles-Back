<?php

namespace App\Security\Voter;

use App\Entity\Adorer;
use App\Entity\Utilisateurs;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdorerVoter extends Voter
{
    public const EDIT = 'ADORER_EDIT';
    public const DELETE = 'ADORER_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Adorer;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Utilisateurs) {
            return false;
        }

        /** @var Adorer $adorer */
        $adorer = $subject;

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($adorer, $user);
            case self::DELETE:
                return $this->canDelete($adorer, $user);
            default:
                return false;
        }
    }

    private function canEdit(Adorer $adorer, Utilisateurs $user): bool
    {
        return $adorer->getUtilisateur() === $user;
    }

    private function canDelete(Adorer $adorer, Utilisateurs $user): bool
    {
        return $adorer->getUtilisateur() === $user;
    }
}
