<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: "caisse")]
 #[ORM\Entity(repositoryClass: "App\Repository\CaisseRepository")]

class Caisse extends AbstractCaisse
{
    
}

