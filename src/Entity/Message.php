<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\Table(name: 'MESSAGES')]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_message')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $contenu = null;

    #[ORM\Column(name: 'piece_jointe', nullable: true)]
    private ?string $pieceJointe = null;

    #[ORM\Column(name: 'date_envoie', type: 'datetime')]
    private ?\DateTimeInterface $dateEnvoie = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'messagesEnvoyes')]
    #[ORM\JoinColumn(name: 'id_Utilisateurs_1', referencedColumnName: 'id', nullable: false)]
    private ?Utilisateurs $expediteur = null;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'messagesRecus')]
    #[ORM\JoinColumn(name: 'id_Utilisateurs_2', referencedColumnName: 'id', nullable: false)]
    private ?Utilisateurs $destinataire = null;

    // Getters / Setters
    public function getId(): ?int
    {
        return $this->id;
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

    public function getPieceJointe(): ?string
    {
        return $this->pieceJointe;
    }

    public function setPieceJointe(?string $pieceJointe): self
    {
        $this->pieceJointe = $pieceJointe;
        return $this;
    }

    public function getDateEnvoie(): ?\DateTimeInterface
    {
        return $this->dateEnvoie;
    }

    public function setDateEnvoie(\DateTimeInterface $dateEnvoie): self
    {
        $this->dateEnvoie = $dateEnvoie;
        return $this;
    }

    public function getExpediteur(): ?Utilisateurs
    {
        return $this->expediteur;
    }

    public function setExpediteur(?Utilisateurs $expediteur): self
    {
        $this->expediteur = $expediteur;
        return $this;
    }

    public function getDestinataire(): ?Utilisateurs
    {
        return $this->destinataire;
    }

    public function setDestinataire(?Utilisateurs $destinataire): self
    {
        $this->destinataire = $destinataire;
        return $this;
    }
}
