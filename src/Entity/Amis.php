<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\AmisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AmisRepository::class)]
#[ORM\Table(name: 'AMIS')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Put(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['amis:read']],
    denormalizationContext: ['groups' => ['amis:write']]
)]
class Amis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_ami')]
    #[Groups(['amis:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['amis:read', 'amis:write'])]
    private ?string $statut = null;

    #[ORM\Column(name: 'date_action', type: 'datetime')]
    #[Groups(['amis:read', 'amis:write'])]
    private ?\DateTimeInterface $dateAction = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'demandesAmisEnvoyees')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id', nullable: false)]
    #[Groups(['amis:read', 'amis:write'])]
    private ?Utilisateurs $demandeur = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'demandesAmisRecues')]
    #[ORM\JoinColumn(name: 'id_utilisateur_2', referencedColumnName: 'id', nullable: false)]
    #[Groups(['amis:read', 'amis:write'])]
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
