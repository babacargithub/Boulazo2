<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class AbstractCaisse
{
    const SODLE_DEBUT_JOUENEE=1;
    const SODLE_FIN_JOUENEE=2;
    const SODLE_DEBUT_JOUENEE_PRECEDENT=11;
    const SODLE_FIN_JOUENEE_PRECEDENT=22;

    #[ORM\Column(name: "id", type: "integer")]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    private $id;

    #[ORM\Column(name: "libelle", type: "string", length: 255, unique: true)]
    private $libelle;

    #[ORM\Column(name: "solde", type: "integer")]
    private $solde;

    #[ORM\Column(name: "lastOp", type: "datetime")]
    private $lastOp;

    #[ORM\ManyToOne(targetEntity: "Shop")]
    #[ORM\JoinColumn(name: "shop", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private $shop;

    public function getId(): int
    {
        return $this->id;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setSolde(int $solde): self
    {
        $this->solde = $solde;
        return $this;
    }

    public function getSolde(): int
    {
        return $this->solde;
    }

    public function setLastOp(\DateTime $lastOp): self
    {
        $this->lastOp = $lastOp;
        return $this;
    }

    public function getLastOp(): \DateTime
    {
        return $this->lastOp;
    }

    public function __toString(): string
    {
        return $this->getLibelle();
    }

    //=================fonctions diverses=================
    public function augmenterSolde(int $montant): void
    {
        $this->solde += $montant;
    }

    public function diminuerSolde(int $montant): void
    {
        $this->solde -= $montant;
    }

    public function setShop(Shop $shop): self
    {
        $this->shop = $shop;
        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }
}
