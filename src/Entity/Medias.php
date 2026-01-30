<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\MediasRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediasRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['medias:read']],
    denormalizationContext: ['groups' => ['medias:write']]
)]
class Medias
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['medias:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['medias:read', 'medias:write'])]
    private ?string $cheminFichier = null;

    #[ORM\Column(length: 255)]
    #[Groups(['medias:read', 'medias:write'])]
    private ?string $nomFichier = null;

    #[ORM\Column(length: 255)]
    #[Groups(['medias:read', 'medias:write'])]
    private ?string $dateUpload = null;

    #[ORM\Column]
    #[Groups(['medias:read', 'medias:write'])]
    private ?int $taille = null;

    #[ORM\ManyToOne(inversedBy: 'medias')]
    #[Groups(['medias:read', 'medias:write'])]
    private ?Ressources $resource = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCheminFichier(): ?string
    {
        return $this->cheminFichier;
    }

    public function setCheminFichier(string $cheminFichier): static
    {
        $this->cheminFichier = $cheminFichier;

        return $this;
    }

    public function getNomFichier(): ?string
    {
        return $this->nomFichier;
    }

    public function setNomFichier(string $nomFichier): static
    {
        $this->nomFichier = $nomFichier;

        return $this;
    }

    public function getDateUpload(): ?string
    {
        return $this->dateUpload;
    }

    public function setDateUpload(string $dateUpload): static
    {
        $this->dateUpload = $dateUpload;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

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
