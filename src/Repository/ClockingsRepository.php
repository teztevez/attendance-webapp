<?php

namespace App\Repository;

use App\Entity\Clockings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Clockings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Clockings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Clockings[]    findAll()
 * @method Clockings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClockingsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Clockings::class);
    }

    // /**
    //  * @return Clockings[] Returns an array of Clockings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Clockings
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
