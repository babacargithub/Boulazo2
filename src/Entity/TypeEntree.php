<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "type_entree")]
#[ORM\Entity(repositoryClass: "App\Repository\TypeEntreeRepository")]
class TypeEntree extends AbstractTypeOperation
{
    #[ORM\Column(name: "produit", type: "boolean", nullable: true)]
    private $produit;



    public function setProduit(?bool $produit): self
    {
        $this->produit = $produit;
        return $this;
    }

    public function getProduit(): ?bool
    {
        return $this->produit;
    }



}
