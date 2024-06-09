<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "op_banque")]
#[ORM\Entity(repositoryClass: "App\Repository\OpBanqueRepository")]
class OpBanque extends BaseClassCaisse
{
    const VERSEMENT = 1;
    const RETRAIT = 2;
    const VIREMENT_EMIS = 3;
    const VIREMENT_RECU = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\CompteBanque")]
    #[ORM\JoinColumn(name: "compte", referencedColumnName: "id", nullable: false)]
    private $compte;

    #[ORM\Column(name: "typeOp", type: "integer")]
    private $typeOp;

    #[ORM\Column(name: "montant", type: "integer")]
    private $montant;

    #[ORM\Column(name: "justif", type: "string", length: 255)]
    private $justif;

    #[ORM\Column(name: "date", type: "datetime")]
    private $date;

    public function getId(): int
    {
        return $this->id;
    }

    public function setTypeOp(int $typeOp): self
    {
        $this->typeOp = $typeOp;
        return $this;
    }

    public function getTypeOp(): int
    {
        return $this->typeOp;
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

    public function setJustif(string $justif): self
    {
        $this->justif = $justif;
        return $this;
    }

    public function getJustif(): string
    {
        return $this->justif;
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

    public function setCompte(CompteBanque $compte): self
    {
        $this->compte = $compte;
        return $this;
    }

    public function getCompte(): ?CompteBanque
    {
        return $this->compte;
    }
}
