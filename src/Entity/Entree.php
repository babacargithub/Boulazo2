<?php

namespace App\Entity;

use App\Repository\EntreeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntreeRepository::class)]
#[ORM\Table(name: "entree")]
class Entree
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "datetime")]
    private \DateTime $date;

    #[ORM\Column(type: "integer")]
    private int $montant;

    #[ORM\ManyToOne(targetEntity: TypeEntree::class)]
    #[ORM\JoinColumn(name: "type_entree", referencedColumnName: "id", nullable: false)]
    private ?TypeEntree $typeEntree;

    #[ORM\ManyToOne(targetEntity: Caisse::class)]
    #[ORM\JoinColumn(name: "caisse", referencedColumnName: "id", nullable: false)]
    private ?Caisse $caisse;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user", referencedColumnName: "id", nullable: false)]
    private $user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $commentaire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $auteur = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getMontant(): int
    {
        return $this->montant;
    }

    public function setTypeEntree(TypeEntree $typeEntree): self
    {
        $this->typeEntree = $typeEntree;
        return $this;
    }

    public function getTypeEntree(): TypeEntree
    {
        return $this->typeEntree;
    }

    public function setCaisse(Caisse $caisse): self
    {
        $this->caisse = $caisse;
        return $this;
    }

    public function getCaisse(): Caisse
    {
        return $this->caisse;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(?string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }
}
