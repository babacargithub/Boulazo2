<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * C'est cette Entité qui permet de garder trace des opérations de sortie ou d'entrée sur la caisse.
 * A chaque sortie ou entrée, une opération de cette entité est crée. Elle permet aussi de connaitre les différents
 * HistoCaisses effectués sur une caisse donnée, mais aussi de retrouver le solde d'une caisse pour une date précise.
 * HistoCaisse
 */
#[ORM\Entity(repositoryClass: "App\Repository\HistoCaisseRepository")]
#[ORM\Table(name: "historique_caisse")]
class HistoCaisse extends BaseClassCaisse
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "id", type: "integer")]
    private int $id;

    /** Type opération qui peut être une sortie ou une entrée */
    #[ORM\Column(name: "typeOp", type: "integer", nullable: true)]
    private ?int $typeOp = null;

    /** l'ancien solde de la caisse concernée avant opération */
    #[ORM\Column(name: "ancienSolde", type: "integer")]
    private int $ancienSolde;

    /** le solde de la caisse concernée après opération */
    #[ORM\Column(name: "nouveauSolde", type: "integer")]
    private int $nouveauSolde;

    #[ORM\Column(name: "dateOp", type: "datetime")]
    private \DateTime $dateOp;

    #[ORM\Column(name: "montant", type: "integer")]
    private int $montant;

    /** Si c'est une sortie, on précise ici la sortie */
    #[ORM\ManyToOne(targetEntity: "App\Entity\Sortie")]
    #[ORM\JoinColumn(name: "sortie", referencedColumnName: "id", onDelete: "NO ACTION", nullable: true)]
    private ?Sortie $sortie = null;

    /** si c'est une entrée, on précise ici l'entrée */
    #[ORM\ManyToOne(targetEntity: "App\Entity\Entree")]
    #[ORM\JoinColumn(name: "entree", referencedColumnName: "id", onDelete: "NO ACTION", nullable: true)]
    private ?Entree $entree = null;

    #[ORM\Column(name: "deleted", type: "boolean", nullable: true)]
    private ?bool $deleted = false;

    #[ORM\Column(name: "deleted_at", type: "datetime", nullable: true)]
    private ?\DateTime $deletedAt = null;

    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setTypeOp(?int $typeOp): self
    {
        $this->typeOp = $typeOp;
        return $this;
    }

    public function getTypeOp(): ?int
    {
        return $this->typeOp;
    }

    public function setAncienSolde(int $ancienSolde): self
    {
        $this->ancienSolde = $ancienSolde;
        return $this;
    }

    public function getAncienSolde(): int
    {
        return $this->ancienSolde;
    }

    public function setNouveauSolde(int $nouveauSolde): self
    {
        $this->nouveauSolde = $nouveauSolde;
        return $this;
    }

    public function getNouveauSolde(): int
    {
        return $this->nouveauSolde;
    }

    public function setDateOp(\DateTime $dateOp): self
    {
        $this->dateOp = $dateOp;
        return $this;
    }

    public function getDateOp(): \DateTime
    {
        return $this->dateOp;
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

    public function setSortie(?Sortie $sortie): self
    {
        $this->sortie = $sortie;
        return $this;
    }

    public function getSortie(): ?Sortie
    {
        return $this->sortie;
    }

    public function setEntree(?Entree $entree): self
    {
        $this->entree = $entree;
        return $this;
    }

    public function getEntree(): ?Entree
    {
        return $this->entree;
    }

    public function getOperation(): ?object
    {
        return $this->sortie ?? $this->entree;
    }

    public function setDeleted(?bool $deleted): self
    {
        $this->deleted = $deleted;
        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeletedAt(?\DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }
}
