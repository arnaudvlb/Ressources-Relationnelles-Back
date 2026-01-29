<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
class Utilisateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoProfil = null;

    #[ORM\Column]
    private ?bool $statusCompte = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateCreation = null;

    #[ORM\ManyToOne(inversedBy: 'utilisateurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RolesUtilisateurs $role = null;

    /**
     * @var Collection<int, RefreshToken>
     */
    #[ORM\OneToMany(targetEntity: RefreshToken::class, mappedBy: 'utilisateur')]
    private Collection $refreshTokens;

    /**
     * @var Collection<int, RenitialisationMdp>
     */
    #[ORM\OneToMany(targetEntity: RenitialisationMdp::class, mappedBy: 'utilisateur')]
    private Collection $renitialisationMdps;

    /**
     * @var Collection<int, Consultation>
     */
    #[ORM\OneToMany(targetEntity: Consultation::class, mappedBy: 'utilisateur')]
    private Collection $consultations;

    /**
     * @var Collection<int, Ressources>
     */
    #[ORM\OneToMany(targetEntity: Ressources::class, mappedBy: 'utilisateur')]
    private Collection $ressources;

    /**
     * @var Collection<int, Commentaires>
     */
    #[ORM\OneToMany(targetEntity: Commentaires::class, mappedBy: 'utilisateur')]
    private Collection $commentaires;

    /**
     * @var Collection<int, Partages>
     */
    #[ORM\OneToMany(targetEntity: Partages::class, mappedBy: 'utilisateur')]
    private Collection $partages;

    /**
     * @var Collection<int, Adorer>
     */
    #[ORM\OneToMany(targetEntity: Adorer::class, mappedBy: 'utilisateur')]
    private Collection $adorers;

    /**
     * @var Collection<int, Favorie>
     */
    #[ORM\OneToMany(targetEntity: Favorie::class, mappedBy: 'utilisateur')]
    private Collection $favories;

    public function __construct()
    {
        $this->refreshTokens = new ArrayCollection();
        $this->renitialisationMdps = new ArrayCollection();
        $this->consultations = new ArrayCollection();
        $this->ressources = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->partages = new ArrayCollection();
        $this->adorers = new ArrayCollection();
        $this->favories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->photoProfil;
    }

    public function setPhotoProfil(?string $photoProfil): static
    {
        $this->photoProfil = $photoProfil;

        return $this;
    }

    public function isStatusCompte(): ?bool
    {
        return $this->statusCompte;
    }

    public function setStatusCompte(bool $statusCompte): static
    {
        $this->statusCompte = $statusCompte;

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

    public function getRole(): ?RolesUtilisateurs
    {
        return $this->role;
    }

    public function setRole(?RolesUtilisateurs $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, RefreshToken>
     */
    public function getRefreshTokens(): Collection
    {
        return $this->refreshTokens;
    }

    public function addRefreshToken(RefreshToken $refreshToken): static
    {
        if (!$this->refreshTokens->contains($refreshToken)) {
            $this->refreshTokens->add($refreshToken);
            $refreshToken->setUtilisateur($this);
        }

        return $this;
    }

    public function removeRefreshToken(RefreshToken $refreshToken): static
    {
        if ($this->refreshTokens->removeElement($refreshToken)) {
            // set the owning side to null (unless already changed)
            if ($refreshToken->getUtilisateur() === $this) {
                $refreshToken->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RenitialisationMdp>
     */
    public function getRenitialisationMdps(): Collection
    {
        return $this->renitialisationMdps;
    }

    public function addRenitialisationMdp(RenitialisationMdp $renitialisationMdp): static
    {
        if (!$this->renitialisationMdps->contains($renitialisationMdp)) {
            $this->renitialisationMdps->add($renitialisationMdp);
            $renitialisationMdp->setUtilisateur($this);
        }

        return $this;
    }

    public function removeRenitialisationMdp(RenitialisationMdp $renitialisationMdp): static
    {
        if ($this->renitialisationMdps->removeElement($renitialisationMdp)) {
            // set the owning side to null (unless already changed)
            if ($renitialisationMdp->getUtilisateur() === $this) {
                $renitialisationMdp->setUtilisateur(null);
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
            $consultation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): static
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getUtilisateur() === $this) {
                $consultation->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ressources>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressources $ressource): static
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setUtilisateur($this);
        }

        return $this;
    }

    public function removeRessource(Ressources $ressource): static
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getUtilisateur() === $this) {
                $ressource->setUtilisateur(null);
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
            $commentaire->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaires $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getUtilisateur() === $this) {
                $commentaire->setUtilisateur(null);
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
            $partage->setUtilisateur($this);
        }

        return $this;
    }

    public function removePartage(Partages $partage): static
    {
        if ($this->partages->removeElement($partage)) {
            // set the owning side to null (unless already changed)
            if ($partage->getUtilisateur() === $this) {
                $partage->setUtilisateur(null);
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
            $adorer->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdorer(Adorer $adorer): static
    {
        if ($this->adorers->removeElement($adorer)) {
            // set the owning side to null (unless already changed)
            if ($adorer->getUtilisateur() === $this) {
                $adorer->setUtilisateur(null);
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
            $favory->setUtilisateur($this);
        }

        return $this;
    }

    public function removeFavory(Favorie $favory): static
    {
        if ($this->favories->removeElement($favory)) {
            // set the owning side to null (unless already changed)
            if ($favory->getUtilisateur() === $this) {
                $favory->setUtilisateur(null);
            }
        }

        return $this;
    }
}
