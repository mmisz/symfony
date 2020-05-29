<?php

namespace App\Repository;

use App\Entity\ListComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method ListComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListComment[]    findAll()
 * @method ListComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListCommentRepository extends ServiceEntityRepository
{
    /**
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    const PAGINATOR_ITEMS_PER_PAGE = 10;

    /**
     * CategoryRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListComment::class);
    }
    public function save(ListComment $listComment): void
    {
        $this->_em->persist($listComment);
        $this->_em->flush($listComment);
    }
    /**
     * Delete record.
     *
     * @param \App\Entity\ListComment $listComment ListComment entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ListComment $listComment): void
    {
        $this->_em->remove($listComment);
        $this->_em->flush($listComment);
    }
    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */

    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder();
    }

    /**
     * Get or create new query builder.
     *
     * @param \Doctrine\ORM\QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('listComment');
    }
}
