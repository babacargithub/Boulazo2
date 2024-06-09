<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "shop")]
#[ORM\Entity(repositoryClass: "App\Repository\ShopRepository")]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 100)]
    private $libelle;

    #[ORM\Column(type: "string", length: 255)]
    private $adresse;

    #[ORM\Column(type: "integer")]
    private $gerant;

    #[ORM\Column(type: "string", length: 255)]
    private $horaire;

    #[ORM\Column(type: "string", length: 100)]
    private $rccm;

    #[ORM\Column(type: "string", length: 100)]
    private $ninea;

    #[ORM\Column(type: "boolean", nullable: true)]
    private $ferme = false;

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

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setGerant(int $gerant): self
    {
        $this->gerant = $gerant;
        return $this;
    }

    public function getGerant(): int
    {
        return $this->gerant;
    }

    public function setHoraire(string $horaire): self
    {
        $this->horaire = $horaire;
        return $this;
    }

    public function getHoraire(): string
    {
        return $this->horaire;
    }

    public function setRccm(string $rccm): self
    {
        $this->rccm = $rccm;
        return $this;
    }

    public function getRccm(): string
    {
        return $this->rccm;
    }

    public function setNinea(string $ninea): self
    {
        $this->ninea = $ninea;
        return $this;
    }

    public function getNinea(): string
    {
        return $this->ninea;
    }

    public function setFerme(bool $ferme): self
    {
        $this->ferme = $ferme;
        return $this;
    }

    public function getFerme(): bool
    {
        return $this->ferme;
    }

    public function __toString(): string
    {
        return $this->libelle;
    }
}
