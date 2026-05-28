<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Ressources;
use App\Entity\Utilisateurs;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RessourceWriteProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $persistProcessor,
        private Security $security,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        if (!$data instanceof Ressources) {
            return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof Utilisateurs) {
            throw new AccessDeniedException('Utilisateur non authentifie.');
        }

        if ($data->getUtilisateur() === null) {
            $data->setUtilisateur($currentUser);
        }

        if ($data->getDateCreation() === null) {
            $data->setDateCreation(new \DateTime());
        }

        $data->setDateModification(new \DateTime());

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
