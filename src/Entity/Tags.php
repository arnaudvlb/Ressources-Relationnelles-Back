<?php

namespace App\Entity;

use App\Repository\TagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagsRepository::class)]
class Tags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    private ?string $couleur = null;

    /**
     * @var Collection<int, TagsRessources>
     */
    #[ORM\OneToMany(targetEntity: TagsRessources::class, mappedBy: 'tag')]
    private Collection $tagsRessources;

    public function __construct()
    {
        $this->tagsRessources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

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
            $tagsRessource->setTag($this);
        }

        return $this;
    }

    public function removeTagsRessource(TagsRessources $tagsRessource): static
    {
        if ($this->tagsRessources->removeElement($tagsRessource)) {
            // set the owning side to null (unless already changed)
            if ($tagsRessource->getTag() === $this) {
                $tagsRessource->setTag(null);
            }
        }

        return $this;
    }
}
