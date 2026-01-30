<?php

namespace App\Entity;

use App\Repository\RessourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RessourcesRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            security: "is_granted('ROLE_USER')"
        ),
        new Put(
            security: "object.getUtilisateur() == user or is_granted('ROLE_ADMIN')"
        ),
        new Delete(
            security: "is_granted('ROLE_ADMIN')"
        ),
    ],
    normalizationContext: ['groups' => ['resource:read']],
    denormalizationContext: ['groups' => ['resource:write']],
    security: "
        object.isEstVisible() == true
        or (is_granted('ROLE_USER') and object.getUtilisateur() == user)
        or is_granted('ROLE_ADMIN')
    "
)]
class Ressources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['resource:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:read', 'resource:write'])]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['resource:read', 'resource:write'])]
    private ?string $contenu = null;

    #[ORM\Column]
    #[Groups(['resource:read'])]
    private ?bool $valide = false;

    #[ORM\Column]
    #[Groups(['resource:read'])]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['resource:read'])]
    private ?\DateTimeImmutable $dateModification = null;

    #[ORM\Column]
    #[Groups(['resource:read', 'resource:write'])]
    private ?bool $estVisible = true;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['resource:read'])]
    private ?Utilisateurs $utilisateur = null;

    #[ORM\OneToMany(targetEntity: Medias::class, mappedBy: 'resource')]
    #[Groups(['resource:read'])]
    private Collection $medias;

    #[ORM\OneToMany(targetEntity: TagsRessources::class, mappedBy: 'resource')]
    #[Groups(['resource:read'])]
    private Collection $tagsRessources;

    #[ORM\OneToMany(targetEntity: Categories::class, mappedBy: 'resource')]
    #[Groups(['resource:read'])]
    private Collection $categories;

    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'resource')]
    private Collection $consultations;

    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'resource')]
    #[Groups(['resource:read'])]
    private Collection $commentaires;

    #[ORM\OneToMany(targetEntity: Partages::class, mappedBy: 'resource')]
    private Collection $partages;

    #[ORM\OneToMany(targetEntity: Adorer::class, mappedBy: 'resource')]
    private Collection $adorers;

    #[ORM\OneToMany(targetEntity: Favoris::class, mappedBy: 'resource')]
    private Collection $favoris;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
        $this->medias = new ArrayCollection();
        $this->tagsRessources = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->partages = new ArrayCollection();
        $this->adorers = new ArrayCollection();
        $this->favoris = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;
        return $this;
    }

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): static
    {
        $this->valide = $valide;
        return $this;
    }

    public function getDateCreation(): ?\DateTimeImmutable
    {
        return $this->dateCreation;
    }

    public function getDateModification(): ?\DateTimeImmutable
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeImmutable $dateModification): static
    {
        $this->dateModification = $dateModification;
        return $this;
    }

    public function isEstVisible(): ?bool
    {
        return $this->estVisible;
    }

    public function setEstVisible(bool $estVisible): static
    {
        $this->estVisible = $estVisible;
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
