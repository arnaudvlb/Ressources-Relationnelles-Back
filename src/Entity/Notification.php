<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\Table(name: 'NOTIFICATIONS')]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_notification')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $type = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: 'text')]
    private ?string $contenu = null;

    #[ORM\Column(type: 'boolean')]
    private bool $lue = false;

    #[ORM\Column(name: 'date_creation', type: 'datetime')]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'notifications')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id', nullable: false)]
    private ?Utilisateurs $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function isLue(): bool
    {
        return $this->lue;
    }

    public function setLue(bool $lue): self
    {
        $this->lue = $lue;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateurs
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateurs $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
}
