<?php

namespace App\Notification\Observers;

use App\Notification\NotificationEvent;
use App\Notification\NotificationObserverInterface;
use App\Entity\Notification;
use App\Entity\Utilisateurs;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Observer qui sauvegarde les notifications dans la base de données
 * Cet observateur crée des enregistrements Notification pour chaque événement
 */
class DatabaseNotificationObserver implements NotificationObserverInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function supports(NotificationEvent $event): bool
    {
        // Cet observateur traite tous les types d'événements
        return true;
    }

    public function update(NotificationEvent $event): void
    {
        try {
            $recipientId = $event->getRecipientId();
            if (!$recipientId) {
                return; // Pas de destinataire
            }

            $recipient = $this->entityManager
                ->getRepository(Utilisateurs::class)
                ->find($recipientId);

            if (!$recipient) {
                return;
            }

            // Créer la notification
            $notification = new Notification();
            $notification->setUtilisateur($recipient);
            $notification->setType($event->getType());
            $notification->setTitre($this->generateTitle($event));
            $notification->setContenu($this->generateContent($event));
            $notification->setLue(false);
            $notification->setDateCreation(new \DateTime());

            $this->entityManager->persist($notification);
            $this->entityManager->flush();

            // Log pour debug
            echo "✓ Notification stockée en BD pour {$recipient->getEmail()}\n";
        } catch (\Exception $e) {
            echo "✗ Erreur DB Notification: " . $e->getMessage() . "\n";
        }
    }

    private function generateTitle(NotificationEvent $event): string
    {
        return match ($event->getType()) {
            'ressource.created' => '📚 Nouvelle ressource ajoutée',
            'commentaire.added' => '💬 Nouveau commentaire',
            'ressource.shared' => '🔗 Ressource partagée avec vous',
            'ami.added' => '👥 Nouvelle amitié',
            default => '🔔 Nouvelle notification',
        };
    }

    private function generateContent(NotificationEvent $event): string
    {
        $data = $event->getData();
        return match ($event->getType()) {
            'ressource.created' => sprintf(
                "%s a créé une ressource : %s",
                $data['author_name'] ?? 'Un utilisateur',
                $data['resource_title'] ?? 'Sans titre'
            ),
            'commentaire.added' => sprintf(
                "%s a commenté : %s",
                $data['commenter_name'] ?? 'Un utilisateur',
                substr($data['comment_text'] ?? '', 0, 50) . '...'
            ),
            'ressource.shared' => sprintf(
                "%s a partagé %s avec vous",
                $data['sharer_name'] ?? 'Un utilisateur',
                $data['resource_title'] ?? 'une ressource'
            ),
            default => json_encode($data),
        };
    }
}
