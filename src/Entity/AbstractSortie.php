<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
class AbstractSortie extends BaseClass
{
    public const SORTIE_LIQUIE = 1;
    public const SORTIE_NON_LIQUIE = 2;

    /** @noinspection PhpPropertyOnlyWrittenInspection */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $montant;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $date;

    // Ce type permet de distinguer les sorties liquides des autres sorties
    #[ORM\Column(type: 'integer')]
    private int $type;

    #[ORM\Column(type: 'string', length: 255)]
    private string $motif;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $commentaire = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\TypeSortie')]
    #[ORM\JoinColumn(name: 'type_sortie', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private TypeSortie $typeSortie;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\Caisse')]
    #[ORM\JoinColumn(name: 'caisse', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Caisse $caisse;

    public function getId(): int
    {
        return $this->id;
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

    public function setDate(\DateTime $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;
        return $this;
    }

    public function getMotif(): string
    {
        return $this->motif;
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

    public function setType(int $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setTypeSortie(TypeSortie $typeSortie): self
    {
        $this->typeSortie = $typeSortie;
        return $this;
    }

    public function getTypeSortie(): TypeSortie
    {
        return $this->typeSortie;
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
