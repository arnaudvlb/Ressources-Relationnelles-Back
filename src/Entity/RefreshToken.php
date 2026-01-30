<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RefreshTokenRepository;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['refresh_token:read']],
    denormalizationContext: ['groups' => ['refresh_token:write']]
)]
class RefreshToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['refresh_token:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['refresh_token:read', 'refresh_token:write'])]
    private ?string $token = null;

    #[ORM\Column]
    #[Groups(['refresh_token:read', 'refresh_token:write'])]
    private ?\DateTimeImmutable $dateExpiration = null;

    #[ORM\Column]
    #[Groups(['refresh_token:read', 'refresh_token:write'])]
    private ?bool $estRevoque = null;

    #[ORM\ManyToOne(inversedBy: 'refreshTokens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['refresh_token:read', 'refresh_token:write'])]
    private ?Utilisateurs $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getDateExpiration(): ?\DateTimeImmutable
    {
        return $this->dateExpiration;
    }

    public function setDateExpiration(\DateTimeImmutable $dateExpiration): static
    {
        $this->dateExpiration = $dateExpiration;

        return $this;
    }

    public function isEstRevoque(): ?bool
    {
        return $this->estRevoque;
    }

    public function setEstRevoque(bool $estRevoque): static
    {
        $this->estRevoque = $estRevoque;

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
}
