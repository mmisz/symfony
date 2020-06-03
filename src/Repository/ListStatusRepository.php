<?php

namespace App\Repository;

use App\Entity\ListStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ListStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListStatus[]    findAll()
 * @method ListStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListStatus::class);
    }

    // /**
    //  * @return ListStatus[] Returns an array of ListStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ListStatus
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
