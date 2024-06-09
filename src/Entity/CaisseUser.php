<?php

namespace App\Entity;

use App\Repository\CaisseUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CaisseUserRepository")
 * @ORM\Table(name="caisse_user")
 */
#[ORM\Entity(repositoryClass: CaisseUserRepository::class)]
#[ORM\Table(name: "caisse_user")]
class CaisseUser extends BaseClassCaisse
{
    public const ACCES_NIVEAU_CAISSIER = 1;
    public const ACCES_NIVEAU_CHEF_CAISSE = 2;
    public const ACCES_NIVEAU_SUPERVISEUR = 3;
    public const ACCES_NIVEAU_ADMIN = 4;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "id", type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Caissier")]
    #[ORM\JoinColumn(name: "caissier", referencedColumnName: "id", onDelete: "CASCADE", nullable: false)]
    private Caissier $caissier;

    #[ORM\Column(name: "allowed", type: "boolean", nullable: true)]
    private ?bool $allowed = true;

    #[ORM\Column(name: "openning_at", type: "time", nullable: true)]
    private ?\DateTimeInterface $openningAt = null;

    #[ORM\Column(name: "closing_at", type: "time", nullable: true)]
    private ?\DateTimeInterface $closingAt = null;

    #[ORM\Column(name: "access_level", type: "integer", nullable: true)]
    private ?int $accessLevel = null;

    #[ORM\Column(name: "comments", type: "string", length: 255, nullable: true)]
    private ?string $comments = null;

    #[ORM\Column(name: "created_at", type: "datetime")]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(name: "deleted", type: "boolean", nullable: true)]
    private ?bool $deleted = null;

    #[ORM\Column(name: "deleted_at", type: "datetime", nullable: true)]
    private ?\DateTimeInterface $deletedAt = null;

    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function setAllowed(bool $allowed): self
    {
        $this->allowed = $allowed;
        return $this;
    }

    public function getAllowed(): bool
    {
        return (bool) $this->allowed;
    }

    public function setOpenningAt(?\DateTimeInterface $openningAt): self
    {
        $this->openningAt = $openningAt;
        return $this;
    }

    public function getOpenningAt(): ?\DateTimeInterface
    {
        return $this->openningAt;
    }

    public function setClosingAt(?\DateTimeInterface $closingAt): self
    {
        $this->closingAt = $closingAt;
        return $this;
    }

    public function getClosingAt(): ?\DateTimeInterface
    {
        return $this->closingAt;
    }

    public function setAccessLevel(?int $accessLevel): self
    {
        $this->accessLevel = $accessLevel;
        return $this;
    }

    public function getAccessLevel(): ?int
    {
        return $this->accessLevel;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;
        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
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

    public function setDeletedAt(?\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setCaissier(Caissier $caissier): self
    {
        $this->caissier = $caissier;
        return $this;
    }

    public function getCaissier(): Caissier
    {
        return $this->caissier;
    }

    public function isAllowed(): bool
    {
        return $this->getAllowed();
    }
}
