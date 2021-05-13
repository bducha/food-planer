<?php

namespace App\Repository;

use App\Entity\ScheduledMeal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ScheduledMeal|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScheduledMeal|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScheduledMeal[]    findAll()
 * @method ScheduledMeal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduledMealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScheduledMeal::class);
    }

    // /**
    //  * @return ScheduledMeal[] Returns an array of ScheduledMeal objects
    //  */
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
    public function findOneBySomeField($value): ?ScheduledMeal
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
