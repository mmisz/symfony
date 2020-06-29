<?php

namespace App\Repository;

use App\Entity\ListElementStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * ListElementStatusRepository class.
 *
 * @method ListElementStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListElementStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListElementStatus[]    findAll()
 * @method ListElementStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListElementStatusRepository extends ServiceEntityRepository
{
    /**
     * ListElementStatusRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListElementStatus::class);
    }

    // /**
    //  * @return ListElementStatus[] Returns an array of ListElementStatus objects
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
    public function findOneBySomeField($value): ?ListElementStatus
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
