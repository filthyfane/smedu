<?php

namespace App\Repository;

use App\Entity\SchoolYear;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SchoolYear|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolYear|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolYear[]    findAll()
 * @method SchoolYear[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolYearRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SchoolYear::class);
    }

    public function findCurrentYear(): ?SchoolYear
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.start_date < :val')
            ->andWhere('s.end_date > :val')
            ->setParameter('val', date("Y-m-d H:i:s"))
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    /**
      * @return SchoolYear[] Returns an array of SchoolYear objects
      */
    public function findPreviousYears()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.end_date < :val')
            ->setParameter('val', date("Y-m-d H:i:s"))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
      * @return SchoolYear[] Returns an array of SchoolYear objects
      */
    public function findNextYears()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.start_date > :val')
            ->setParameter('val', date("Y-m-d H:i:s"))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
      * @return SchoolYear[] Returns an array of SchoolYear objects
      */
    public function findCurrentAndNew()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.end_date > :val')
            ->setParameter('val', date("Y-m-d H:i:s"))
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return SchoolYear[] Returns an array of SchoolYear objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SchoolYear
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
