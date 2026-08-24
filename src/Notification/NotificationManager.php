<?php

namespace App\Notification;

/**
 * Gestionnaire centralisé des notifications (SUJET du pattern Observer)
 * C'est le centre de contrôle qui gère tous les observateurs
 */
class NotificationManager
{
    /** @var NotificationObserverInterface[] */
    private array $observers = [];

    /**
     * Enregistre un observateur
     */
    public function attach(NotificationObserverInterface $observer): void
    {
        if (!in_array($observer, $this->observers, true)) {
            $this->observers[] = $observer;
        }
    }

    /**
     * Détache un observateur
     */
    public function detach(NotificationObserverInterface $observer): void
    {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    /**
     * Notifie tous les observateurs d'un événement
     * C'est la méthode principale !
     */
    public function notify(NotificationEvent $event): void
    {
        foreach ($this->observers as $observer) {
            if ($observer->supports($event)) {
                $observer->update($event);
            }
        }
    }

    /**
     * Obtient tous les observateurs enregistrés
     */
    public function getObservers(): array
    {
        return $this->observers;
    }
}
