<?php

namespace App\Notification\Observers;

use App\Notification\NotificationEvent;
use App\Notification\NotificationObserverInterface;


class EmailNotificationObserver implements NotificationObserverInterface
{
    public function supports(NotificationEvent $event): bool
    {
        $importantEvents = ['ressource.shared', 'ami.added', 'commentaire.added'];
        return in_array($event->getType(), $importantEvents);
    }

    public function update(NotificationEvent $event): void
    {
        try {
           
            $data = $event->getData();
            $recipientId = $event->getRecipientId();

            echo "📧 Email observé pour notification: " . $event->getType() . "\n";

            

        } catch (\Exception $e) {
            echo "✗ Erreur Email Notification: " . $e->getMessage() . "\n";
        }
    }
}
