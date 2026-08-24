<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Récupère les messages entre deux utilisateurs (conversation privée)
     */
    public function findConversation(int $userA, int $userB): array
    {
        return $this->createQueryBuilder('m')
            ->where('(m.expediteur = :a AND m.destinataire = :b)')
            ->orWhere('(m.expediteur = :b AND m.destinataire = :a)')
            ->setParameter('a', $userA)
            ->setParameter('b', $userB)
            ->orderBy('m.dateEnvoie', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les messages reçus par un utilisateur
     */
    public function findMessagesRecus(int $userId): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.destinataire = :id')
            ->setParameter('id', $userId)
            ->orderBy('m.dateEnvoie', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les messages envoyés par un utilisateur
     */
    public function findMessagesEnvoyes(int $userId): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.expediteur = :id')
            ->setParameter('id', $userId)
            ->orderBy('m.dateEnvoie', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
