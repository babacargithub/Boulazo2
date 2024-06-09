<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "sortie")]
#[ORM\Entity(repositoryClass: "App\Repository\SortieRepository")]
class Sortie extends AbstractSortie
{
    #[ORM\Column(name: "justif_url", type: "string", length: 255, nullable: true)]
    private ?string $justifUrl;

    #[ORM\Column(name: "beneficiaire", type: "string", length: 255, nullable: true)]
    private ?string $beneficiaire;

    public function setJustifUrl(?string $justifUrl): self
    {
        $this->justifUrl = $justifUrl;
        return $this;
    }

    public function getJustifUrl(): ?string
    {
        return $this->justifUrl;
    }

    public function setBeneficiaire(?string $beneficiaire): self
    {
        $this->beneficiaire = $beneficiaire;
        return $this;
    }

    public function getBeneficiaire(): ?string
    {
        return $this->beneficiaire;
    }
}
