<?php

namespace App\Notification;

/**
 * Interface pour les observateurs de notifications
 * Tout observateur DOIT implémenter cette interface
 */
interface NotificationObserverInterface
{
    /**
     * Appelé quand un événement de notification se produit
     * 
     * @param NotificationEvent $event L'événement de notification
     * @return void
     */
    public function update(NotificationEvent $event): void;

    /**
     * Détermine si cet observateur doit réagir à cet événement
     * 
     * @param NotificationEvent $event L'événement
     * @return bool
     */
    public function supports(NotificationEvent $event): bool;
}
