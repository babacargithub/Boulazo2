<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass()]
abstract class AbstractTypeOperation
{
const ENTREE_LIQUIDE = 1;
const SORTIE_LIQUIDE = 2;
const VERSEMENT_BANQUE_EMIS = 3;
const VIREMENT_BANQUE_EMIS = 4;
const VERSEMENT_BANQUE_RECU = 5;
const VIREMENT_BANQUE_RECU = 6;
const AUTRE_OPERATION = 0;

#[ORM\Id]
#[ORM\GeneratedValue(strategy: 'AUTO')]
#[ORM\Column(type: 'integer')]
private int $id;

#[ORM\Column(type: 'string', length: 255, unique: true, nullable: true)]
private ?string $libelle;

#[ORM\Column(type: 'string', length: 255, nullable: true)]
private ?string $commentaire = null;

#[ORM\Column(type: 'integer', nullable: true)]
private ?int $categorie = null;

public function getId(): int
{
return $this->id;
}

public function setLibelle(string $libelle): self
{
$this->libelle = $libelle;
return $this;
}

public function getLibelle(): ?string
{
return $this->libelle;
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

public function setCategorie(?int $categorie): self
{
$this->categorie = $categorie;
return $this;
}

public function getCategorie(): ?int
{
return $this->categorie;
}

public function __toString(): string
{
return $this->getLibelle().'';
}
}
