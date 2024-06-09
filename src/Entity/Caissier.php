<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "caissier")]
#[ORM\Entity(repositoryClass: "App\Repository\CaissierRepository")]
class Caissier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(name: "prenom", type: "string", length: 255)]
    private $prenom;

    #[ORM\Column(name: "nom", type: "string", length: 100)]
    private $nom;

    #[ORM\Column(name: "tel", type: "bigint", unique: true)]
    private $tel;

    #[ORM\Column(name: "adresse", type: "string", length: 255, nullable: true)]
    private $adresse;

    #[ORM\Column(name: "shortName", type: "string", length: 6, unique: true, nullable: true)]
    private $shortName;

    #[ORM\Column(name: "disabled", type: "boolean")]
    private $disabled = false;

    #[ORM\Column(name: "created_at", type: "datetime", nullable: true)]
    private $createdAt;

    #[ORM\Column(name: "deleated", type: "boolean", nullable: true)]
    private $deleated = false;

    #[ORM\Column(name: "deleated_at", type: "datetime", nullable: true)]
    private $deleatedAt;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_account", referencedColumnName: "id", nullable: false)]
    private $userAccount;

    public function setUserAccount(User $user): self
    {
        $this->userAccount = $user;
        return $this;
    }

    public function getUserAccount(): ?User
    {
        return $this->userAccount;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;
        return $this;
    }

    public function getTel(): int
    {
        return $this->tel;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setShortName(?string $shortName): self
    {
        $this->shortName = $shortName;
        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setDisabled(bool $disabled): self
    {
        $this->disabled = $disabled;
        return $this;
    }

    public function getDisabled(): bool
    {
        return $this->disabled;
    }

    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setDeleated(?bool $deleated): self
    {
        $this->deleated = $deleated;
        return $this;
    }

    public function getDeleated(): ?bool
    {
        return $this->deleated;
    }

    public function setDeleatedAt(?\DateTime $deleatedAt): self
    {
        $this->deleatedAt = $deleatedAt;
        return $this;
    }

    public function getDeleatedAt(): ?\DateTime
    {
        return $this->deleatedAt;
    }

    public function __toString(): string
    {
        return ucfirst($this->prenom) . ' ' . strtoupper($this->nom);
    }
}
