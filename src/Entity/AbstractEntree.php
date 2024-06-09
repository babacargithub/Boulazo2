<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
class AbstractEntree
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(name: "libelle", type: "string", length: 255, nullable: true)]
    private $libelle;

    #[ORM\Column(name: "date", type: "datetime")]
    private $date;

    #[ORM\Column(name: "montant", type: "integer")]
    private $montant;

    #[ORM\Column(name: "commentaire", type: "text", nullable: true)]
    private $commentaire;

    #[ORM\ManyToOne(targetEntity: "App\Entity\TypeEntree")]
    #[ORM\JoinColumn(name: "type_entree", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private $typeEntree;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Caisse")]
    #[ORM\JoinColumn(name: "caisse", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private $caisse;

    public function getId(): int
    {
        return $this->id;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
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

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function __toString(): string
    {
        return $this->getLibelle();
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
}
