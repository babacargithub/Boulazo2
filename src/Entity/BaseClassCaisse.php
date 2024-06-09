<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\MappedSuperclass]
class BaseClassCaisse
{
    #[ORM\ManyToOne(targetEntity: Caisse::class)]
    #[ORM\JoinColumn(name: "caisse", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Caisse $caisse;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user", referencedColumnName: "id", nullable: false)]
    private UserInterface $user;

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
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
