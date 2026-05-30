<?php

namespace App\Notification\Observers;

use App\Notification\NotificationEvent;
use App\Notification\NotificationObserverInterface;
use Psr\Log\LoggerInterface;

/**
 * Observer qui log les événements de notification
 * Utile pour le debugging et l'audit
 */
class LogNotificationObserver implements NotificationObserverInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {}

    public function supports(NotificationEvent $event): bool
    {
        return true;
    }

    public function update(NotificationEvent $event): void
    {
        $this->logger->info('Notification envoyée', [
            'type' => $event->getType(),
            'recipient' => $event->getRecipientId(),
            'data' => $event->getData(),
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);
    }
}
