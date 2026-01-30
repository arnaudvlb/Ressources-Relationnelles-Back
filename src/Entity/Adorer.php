<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AdorerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdorerRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['adorer:read']],
    denormalizationContext: ['groups' => ['adorer:write']]
)]
class Adorer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['adorer:read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['adorer:read', 'adorer:write'])]
    private ?\DateTimeImmutable $dateAdorer = null;

    #[ORM\ManyToOne(inversedBy: 'adorers')]
    #[Groups(['adorer:read', 'adorer:write'])]
    private ?Utilisateurs $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'adorers')]
    #[Groups(['adorer:read', 'adorer:write'])]
    private ?Ressources $resource = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateAdorer(): ?\DateTimeImmutable
    {
        return $this->dateAdorer;
    }

    public function setDateAdorer(\DateTimeImmutable $dateAdorer): static
    {
        $this->dateAdorer = $dateAdorer;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateurs $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getResource(): ?Ressources
    {
        return $this->resource;
    }

    public function setResource(?Ressources $resource): static
    {
        $this->resource = $resource;

        return $this;
    }
}
