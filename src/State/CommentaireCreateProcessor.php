<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Commentaires;
use App\Entity\Utilisateurs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CommentaireCreateProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security
    ) {}

    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data instanceof Commentaires) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof Utilisateurs) {
            throw new AccessDeniedException('Utilisateur non authentifie.');
        }

        $payloadUserId = $data->getIdUser();
        if ($payloadUserId !== null && $payloadUserId !== $currentUser->getId()) {
            throw new AccessDeniedException('Le champ id_user doit correspondre a l utilisateur connecte.');
        }

        $data->setUtilisateur($currentUser);

        if ($data->getDateCreation() === null) {
            $data->setDateCreation(new \DateTime());
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
