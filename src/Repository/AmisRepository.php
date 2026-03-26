<?php

namespace App\Repository;

use App\Entity\Amis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AmisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Amis::class);
    }

    /**
     * Vérifie si deux utilisateurs sont amis ou ont une demande en cours
     */
    public function relationExiste(int $userA, int $userB): ?Amis
    {
        return $this->createQueryBuilder('a')
            ->where('(a.demandeur = :a AND a.ami = :b)')
            ->orWhere('(a.demandeur = :b AND a.ami = :a)')
            ->setParameter('a', $userA)
            ->setParameter('b', $userB)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Liste des amis acceptés d’un utilisateur
     */
    public function findAmisAcceptes(int $userId): array
    {
        return $this->createQueryBuilder('a')
            ->where('(a.demandeur = :id OR a.ami = :id)')
            ->andWhere('a.statut = :ok')
            ->setParameter('id', $userId)
            ->setParameter('ok', 'accepte')
            ->getQuery()
            ->getResult();
    }

    /**
     * Demandes d'amis reçues en attente
     */
    public function findDemandesRecues(int $userId): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.ami = :id')
            ->andWhere('a.statut = :attente')
            ->setParameter('id', $userId)
            ->setParameter('attente', 'en_attente')
            ->getQuery()
            ->getResult();
    }

    /**
     * Demandes d'amis envoyées en attente
     */
    public function findDemandesEnvoyees(int $userId): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.demandeur = :id')
            ->andWhere('a.statut = :attente')
            ->setParameter('id', $userId)
            ->setParameter('attente', 'en_attente')
            ->getQuery()
            ->getResult();
    }
}
