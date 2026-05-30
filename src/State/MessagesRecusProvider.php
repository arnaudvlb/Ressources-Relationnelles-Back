<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Message;
use App\Entity\Utilisateurs;
use App\Repository\MessageRepository;
use Symfony\Bundle\SecurityBundle\Security;

class MessagesRecusProvider implements ProviderInterface
{
    public function __construct(
        private MessageRepository $messageRepository,
        private Security $security,
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof Utilisateurs) {
            return [];
        }

        return $this->messageRepository->findMessagesRecus($currentUser->getId());
    }
}
