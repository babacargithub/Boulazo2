<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "shop_user")]
#[ORM\Entity(repositoryClass: "App\Repository\ShopUserRepository")]
class ShopUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\ManyToOne(targetEntity: "App\Entity\Shop")]
    #[ORM\JoinColumn(name: "shop", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private $shop;

    #[ORM\ManyToOne(targetEntity: "App\Entity\User")]
    #[ORM\JoinColumn(name: "user", referencedColumnName: "id", nullable: false)]
    private $user;

    #[ORM\Column(type: "boolean")]
    private $disabled = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $disabledAt;

    #[ORM\Column(type: "boolean")]
    private $locked = false;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $lockedAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $lockedUntil;

    #[ORM\Column(type: "datetime")]
    private $lastLogin;

    public function __construct()
    {
        $this->lastLogin = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
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

    public function setDisabledAt(?\DateTime $disabledAt): self
    {
        $this->disabledAt = $disabledAt;
        return $this;
    }

    public function getDisabledAt(): ?\DateTime
    {
        return $this->disabledAt;
    }

    public function setLocked(bool $locked): self
    {
        $this->locked = $locked;
        return $this;
    }

    public function getLocked(): bool
    {
        return $this->locked;
    }

    public function setLockedAt(?\DateTime $lockedAt): self
    {
        $this->lockedAt = $lockedAt;
        return $this;
    }

    public function getLockedAt(): ?\DateTime
    {
        return $this->lockedAt;
    }

    public function setLockedUntil(?\DateTime $lockedUntil): self
    {
        $this->lockedUntil = $lockedUntil;
        return $this;
    }

    public function getLockedUntil(): ?\DateTime
    {
        return $this->lockedUntil;
    }

    public function setLastLogin(\DateTime $lastLogin): self
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function getLastLogin(): \DateTime
    {
        return $this->lastLogin;
    }

    public function isLocked(): bool
    {
        return $this->getLocked();
    }

    public function isDisabled(): bool
    {
        return $this->getDisabled();
    }

    public function setShop(Shop $shop): self
    {
        $this->shop = $shop;
        return $this;
    }

    public function getShop(): ?Shop
    {
        return $this->shop;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }
}
