<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "compte_banque")]
#[ORM\Entity(repositoryClass: "App\Repository\CompteBanqueRepository")]
class CompteBanque
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "id", type: "integer")]
    private int $id;

    #[ORM\Column(name: "libelle", type: "string", length: 255, unique: true, nullable: false)]
    private string $libelle;

    #[ORM\Column(name: "numCompte", type: "bigint", unique: true)]
    private int $numCompte;

    #[ORM\Column(name: "banque", type: "string", length: 100)]
    private string $banque;

    #[ORM\Column(name: "solde", type: "integer")]
    private int $solde;

    #[ORM\Column(name: "lastOp", type: "datetime", nullable: true)]
    private ?\DateTime $lastOp = null;

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

    public function setNumCompte(int $numCompte): self
    {
        $this->numCompte = $numCompte;
        return $this;
    }

    public function getNumCompte(): int
    {
        return $this->numCompte;
    }

    public function setBanque(string $banque): self
    {
        $this->banque = $banque;
        return $this;
    }

    public function getBanque(): string
    {
        return $this->banque;
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

    public function setLastOp(?\DateTime $lastOp): self
    {
        $this->lastOp = $lastOp;
        return $this;
    }

    public function getLastOp(): ?\DateTime
    {
        return $this->lastOp;
    }

    public function __toString(): string
    {
        return $this->libelle;
    }

    public function augmenterSolde(int $montant): void
    {
        $this->solde += $montant;
    }

    public function diminuerSolde(int $montant): void
    {
        $this->solde -= $montant;
    }
}
