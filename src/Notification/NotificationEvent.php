<?php

namespace App\Notification;

/**
 * Représente un événement de notification
 * C'est le message envoyé aux observateurs
 */
class NotificationEvent
{
    public function __construct(
        private string $type,                    
        private array $data,                     // Les données de l'événement
        private string $recipientId = '',        // ID de l'utilisateur destinataire
        private array $metadata = []             // Métadonnées supplémentaires
    ) {}

    public function getType(): string
    {
        return $this->type;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getRecipientId(): string
    {
        return $this->recipientId;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function setRecipientId(string $recipientId): self
    {
        $this->recipientId = $recipientId;
        return $this;
    }

    public function addMetadata(string $key, mixed $value): self
    {
        $this->metadata[$key] = $value;
        return $this;
    }
}
