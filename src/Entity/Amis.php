<?php

namespace App\Entity;

use App\Repository\AmisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmisRepository::class)]
#[ORM\Table(name: 'AMIS')]
class Amis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_ami')]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(name: 'date_action', type: 'datetime')]
    private ?\DateTimeInterface $dateAction = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'demandesAmisEnvoyees')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: false)]
    private ?Utilisateurs $demandeur = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'demandesAmisRecues')]
    #[ORM\JoinColumn(name: 'id_utilisateur_2', referencedColumnName: 'id_utilisateur', nullable: false)]
    private ?Utilisateurs $ami = null;

    // Getters / Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateAction(): ?\DateTimeInterface
    {
        return $this->dateAction;
    }

    public function setDateAction(\DateTimeInterface $dateAction): self
    {
        $this->dateAction = $dateAction;
        return $this;
    }

    public function getDemandeur(): ?Utilisateurs
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Utilisateurs $demandeur): self
    {
        $this->demandeur = $demandeur;
        return $this;
    }

    public function getAmi(): ?Utilisateurs
    {
        return $this->ami;
    }

    public function setAmi(?Utilisateurs $ami): self
    {
        $this->ami = $ami;
        return $this;
    }
}
