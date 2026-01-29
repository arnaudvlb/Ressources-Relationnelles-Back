<?php

namespace App\Entity;

use App\Repository\RessourcesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RessourcesRepository::class)]
class Ressources
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $contenu = null;

    #[ORM\Column]
    private ?bool $valide = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateModification = null;

    #[ORM\Column]
    private ?bool $estVisible = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    private ?Utilisateurs $utilisateur = null;

    /**
     * @var Collection<int, Medias>
     */
    #[ORM\OneToMany(targetEntity: Medias::class, mappedBy: 'resource')]
    private Collection $medias;

    /**
     * @var Collection<int, TagsRessources>
     */
    #[ORM\OneToMany(targetEntity: TagsRessources::class, mappedBy: 'resource')]
    private Collection $tagsRessources;

    /**
     * @var Collection<int, Categories>
     */
    #[ORM\OneToMany(targetEntity: Categories::class, mappedBy: 'resource')]
    private Collection $categories;

    /**
     * @var Collection<int, Consultation>
     */
    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'resource')]
    private Collection $consultations;

    /**
     * @var Collection<int, Commentaires>
     */
    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'resource')]
    private Collection $commentaires;

    /**
     * @var Collection<int, Partages>
     */
    #[ORM\OneToMany(targetEntity: Partages::class, mappedBy: 'resource')]
    private Collection $partages;

    /**
     * @var Collection<int, Adorer>
     */
    #[ORM\OneToMany(targetEntity: Adorer::class, mappedBy: 'resource')]
    private Collection $adorers;

    /**
     * @var Collection<int, Favorie>
     */
    #[ORM\OneToMany(targetEntity: Favorie::class, mappedBy: 'resource')]
    private Collection $favories;

    public function __construct()
    {
        $this->medias = new ArrayCollection();
        $this->tagsRessources = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->partages = new ArrayCollection();
        $this->adorers = new ArrayCollection();
        $this->favories = new ArrayCollection();
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

    public function setDateCreation(\DateTimeImmutable $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
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

    /**
     * @return Collection<int, Medias>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addMedia(Medias $media): static
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setResource($this);
        }

        return $this;
    }

    public function removeMedia(Medias $media): static
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getResource() === $this) {
                $media->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, TagsRessources>
     */
    public function getTagsRessources(): Collection
    {
        return $this->tagsRessources;
    }

    public function addTagsRessource(TagsRessources $tagsRessource): static
    {
        if (!$this->tagsRessources->contains($tagsRessource)) {
            $this->tagsRessources->add($tagsRessource);
            $tagsRessource->setResource($this);
        }

        return $this;
    }

    public function removeTagsRessource(TagsRessources $tagsRessource): static
    {
        if ($this->tagsRessources->removeElement($tagsRessource)) {
            // set the owning side to null (unless already changed)
            if ($tagsRessource->getResource() === $this) {
                $tagsRessource->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Categories>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setResource($this);
        }

        return $this;
    }

    public function removeCategory(Categories $category): static
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getResource() === $this) {
                $category->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): static
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations->add($consultation);
            $consultation->setResource($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getResource() === $this) {
                $consultation->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commentaires>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaires $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setResource($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getResource() === $this) {
                $commentaire->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Partages>
     */
    public function getPartages(): Collection
    {
        return $this->partages;
    }

    public function addPartage(Partages $partage): static
    {
        if (!$this->partages->contains($partage)) {
            $this->partages->add($partage);
            $partage->setResource($this);
        }

        return $this;
    }

    public function removePartage(Partages $partage): static
    {
        if ($this->partages->removeElement($partage)) {
            // set the owning side to null (unless already changed)
            if ($partage->getResource() === $this) {
                $partage->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Adorer>
     */
    public function getAdorers(): Collection
    {
        return $this->adorers;
    }

    public function addAdorer(Adorer $adorer): static
    {
        if (!$this->adorers->contains($adorer)) {
            $this->adorers->add($adorer);
            $adorer->setResource($this);
        }

        return $this;
    }

    public function removeAdorer(Adorer $adorer): static
    {
        if ($this->adorers->removeElement($adorer)) {
            // set the owning side to null (unless already changed)
            if ($adorer->getResource() === $this) {
                $adorer->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorie>
     */
    public function getFavories(): Collection
    {
        return $this->favories;
    }

    public function addFavory(Favorie $favory): static
    {
        if (!$this->favories->contains($favory)) {
            $this->favories->add($favory);
            $favory->setResource($this);
        }

        return $this;
    }

    public function removeFavory(Favorie $favory): static
    {
        if ($this->favories->removeElement($favory)) {
            // set the owning side to null (unless already changed)
            if ($favory->getResource() === $this) {
                $favory->setResource(null);
            }
        }

        return $this;
    }
}
