<?php

namespace App\Entity;

use App\Repository\PartagesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartagesRepository::class)]
class Partages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datePartage = null;

    #[ORM\ManyToOne(inversedBy: 'partages')]
    private ?Utilisateurs $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'partages')]
    private ?Utilisateurs $utilisateur2 = null;

    #[ORM\ManyToOne(inversedBy: 'partages')]
    private ?Ressources $resource = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePartage(): ?\DateTimeImmutable
    {
        return $this->datePartage;
    }

    public function setDatePartage(\DateTimeImmutable $datePartage): static
    {
        $this->datePartage = $datePartage;

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

    public function getUtilisateur2(): ?Utilisateurs
    {
        return $this->utilisateur2;
    }

    public function setUtilisateur2(?Utilisateurs $utilisateur2): static
    {
        $this->utilisateur2 = $utilisateur2;

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
