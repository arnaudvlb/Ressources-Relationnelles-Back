<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Ressources;
use App\Repository\RessourcesRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * State Provider for Ressources collection with filtering and visibility management.
 * 
 * Features:
 * - Visibility filtering (PUBLIC/AMI/PRIVE)
 * - Search by title and content
 * - Filter by tags
 * - Filter by categories
 * - Filter by media presence
 */
class RessourcesCollectionProvider implements ProviderInterface
{
    public function __construct(
        private RessourcesRepository $ressourcesRepository,
        private Security $security
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $request = $context['request'] ?? null;
        if (!$request) {
            return [];
        }

        $qb = $this->ressourcesRepository->createQueryBuilder('r');
        $currentUser = $this->security->getUser();

        $this->applyVisibilityFilter($qb, $currentUser);

        if ($search = $request->query->get('search')) {
            $qb->andWhere('(r.titre LIKE :search OR r.contenu LIKE :search)')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($tags = $request->query->get('tags')) {
            $tagIds = is_array($tags) ? $tags : array_filter(array_map('trim', explode(',', $tags)));
            if (!empty($tagIds)) {
                $qb->leftJoin('r.tagsRessources', 'tr')
                    ->leftJoin('tr.tag', 't')
                    ->andWhere('t.id IN (:tagIds)')
                    ->setParameter('tagIds', array_map('intval', $tagIds))
                    ->addGroupBy('r.id');
            }
        }

        if ($categories = $request->query->get('categories')) {
            $categoryIds = is_array($categories) ? $categories : array_filter(array_map('trim', explode(',', $categories)));
            if (!empty($categoryIds)) {
                $qb->leftJoin('r.categorie', 'c')
                    ->andWhere('c.id IN (:categoryIds)')
                    ->setParameter('categoryIds', array_map('intval', $categoryIds))
                    ->addGroupBy('r.id');
            }
        }

        if ($hasMedia = $request->query->get('hasMedia')) {
            $qb->leftJoin('r.medias', 'm');
            if ($hasMedia === 'true' || $hasMedia === '1') {
                $qb->andWhere('m.id IS NOT NULL');
            } elseif ($hasMedia === 'false' || $hasMedia === '0') {
                $qb->andWhere('m.id IS NULL');
            }
            $qb->addGroupBy('r.id');
        }

        $qb->orderBy('r.dateCreation', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Apply visibility filter based on authentication status
     */
    private function applyVisibilityFilter($qb, $currentUser): void
    {
        if (!$currentUser) {
            $qb->andWhere("r.visibilite = 'public'");
        } else {
            $qb->andWhere("(
                r.visibilite = 'public' 
                OR (r.utilisateur = :user AND r.visibilite = 'private')
                OR (r.visibilite = 'friend' AND EXISTS (
                    SELECT 1 FROM App\Entity\Amis a 
                    WHERE (a.demandeur = :user AND a.ami = r.utilisateur AND a.statut = :accepted)
                    OR (a.ami = :user AND a.demandeur = r.utilisateur AND a.statut = :accepted)
                ))
            )")
                ->setParameter('user', $currentUser)
                ->setParameter('accepted', 'accepted');
        }
    }
}
