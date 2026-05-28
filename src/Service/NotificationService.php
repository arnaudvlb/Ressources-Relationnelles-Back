<?php

namespace App\Service;

use App\Notification\NotificationEvent;
use App\Notification\NotificationManager;
use App\Notification\Observers\DatabaseNotificationObserver;
use App\Notification\Observers\EmailNotificationObserver;
use App\Notification\Observers\LogNotificationObserver;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Service qui initialise et gère les notifications
 * Ce service est responsable de l'enregistrement de tous les observateurs
 */
class NotificationService
{
    private NotificationManager $notificationManager;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {
        $this->notificationManager = new NotificationManager();
        $this->initializeObservers();
    }

    /**
     * Enregistre tous les observateurs auprès du gestionnaire
     */
    private function initializeObservers(): void
    {
        $this->notificationManager->attach(
            new DatabaseNotificationObserver($this->entityManager)
        );

        $this->notificationManager->attach(
            new EmailNotificationObserver()
        );

        $this->notificationManager->attach(
            new LogNotificationObserver($this->logger)
        );

    }

    /**
     * Notifie des événements (interface publique)
     */
    public function notify(NotificationEvent $event): void
    {
        $this->notificationManager->notify($event);
    }

    /**
     * Helper rapide pour créer et envoyer une notification
     */
    public function sendNotification(
        string $type,
        array $data,
        string $recipientId,
        array $metadata = []
    ): void {
        $event = new NotificationEvent($type, $data, $recipientId, $metadata);
        $this->notify($event);
    }

    /**
     * Ajouter un observateur personnalisé
     */
    public function addObserver($observer): void
    {
        $this->notificationManager->attach($observer);
    }
}
