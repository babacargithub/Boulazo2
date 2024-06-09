<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


 #[ORM\Table(name: "type_operation")]
 #[ORM\Entity(repositoryClass: "App\Repository\TypeOperationRepository")]

class TypeOperation extends AbstractTypeOperation
{



}

