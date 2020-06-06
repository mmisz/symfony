<?php

namespace App\Repository;

use App\Entity\NoteTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NoteTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method NoteTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method NoteTag[]    findAll()
 * @method NoteTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteTagRepository extends ServiceEntityRepository
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
    const PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * NoteTagRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(\Doctrine\Common\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, NoteTag::class);
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
        return $queryBuilder ?? $this->createQueryBuilder('noteTag');
    }

    /**
     * @param NoteTag $noteTag
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(NoteTag $noteTag)
    {
        $this->_em->persist($noteTag);
        $this->_em->flush($noteTag);
    }

    /**
     * @param string $name
     * @return NoteTag
     */
    public function findOneByName(string $name)
    {
        return $this->findOneBy(
            ['name' => $name]
        );
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\NoteTag $noteTag NoteTag entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(NoteTag $noteTag): void
    {
        $this->_em->remove($noteTag);
        $this->_em->flush($noteTag);
    }
}
