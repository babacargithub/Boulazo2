<?php

namespace App\Repository;

use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * ShopRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShopRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }

    public function getListe()
    {
        return $qb = $this->createQueryBuilder('s')->where('s.ferme=0');
    }

    public function findAll(): array
    {
        return $qb = $this->createQueryBuilder('s')->where('s.ferme=0')->getQuery()->getResult();
    }

    public function getOtherShops(Session $session): QueryBuilder
    {
        return $this->createQueryBuilder('s')
            ->where('s.id<>:id')
            ->setParameter('id', $session->get('connected_shop')->getId());
    }
}
