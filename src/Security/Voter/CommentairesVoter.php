<?php

namespace App\Security\Voter;

use App\Entity\Commentaires;
use App\Entity\Utilisateurs;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentairesVoter extends Voter
{
    public const EDIT = 'COMMENTAIRE_EDIT';
    public const DELETE = 'COMMENTAIRE_DELETE';
    public const VIEW = 'COMMENTAIRE_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::VIEW])
            && $subject instanceof Commentaires;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof Utilisateurs) {
            return false;
        }

        /** @var Commentaires $commentaire */
        $commentaire = $subject;

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($commentaire, $user);
            case self::EDIT:
                return $this->canEdit($commentaire, $user);
            case self::DELETE:
                return $this->canDelete($commentaire, $user);
            default:
                return false;
        }
    }

    private function canView(Commentaires $commentaire, Utilisateurs $user): bool
    {
        return true;
    }

    private function canEdit(Commentaires $commentaire, Utilisateurs $user): bool
    {
        return $commentaire->getUtilisateur() === $user;
    }

    private function canDelete(Commentaires $commentaire, Utilisateurs $user): bool
    {
        return $commentaire->getUtilisateur() === $user;
    }
}
