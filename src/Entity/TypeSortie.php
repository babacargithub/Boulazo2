<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "type_sortie")]
#[ORM\Entity(repositoryClass: "App\Repository\TypeSortieRepository")]
class TypeSortie extends AbstractTypeOperation
{
    #[ORM\Column(name: "charge", type: "boolean", nullable: true)]
    private $charge;

    public function setCharge(?bool $charge): self
    {
        $this->charge = $charge;
        return $this;
    }

    public function getCharge(): ?bool
    {
        return $this->charge;
    }
}
