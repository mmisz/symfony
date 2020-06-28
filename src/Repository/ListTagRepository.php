<?php

namespace App\Repository;

use App\Entity\ListTag;
use App\Entity\ToDoList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * ListTagRepository class.
 * @method ListTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListTag[]    findAll()
 * @method ListTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListTagRepository extends ServiceEntityRepository
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
     * ListTagRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListTag::class);
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
        return $queryBuilder ?? $this->createQueryBuilder('listTag');
    }

    /**
     * save tag.
     * @param ListTag $listTag
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ListTag $listTag)
    {
        $this->_em->persist($listTag);
        $this->_em->flush($listTag);
    }

    /**
     * find by name.
     * @param string $name
     * @return ListTag|null
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
     * @param \App\Entity\ListTag $listTag ListTag entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ListTag $listTag): void
    {
        $this->_em->remove($listTag);
        $this->_em->flush($listTag);
    }
    /**
     * find tags by author of content.
     * @param User $user
     * @return QueryBuilder
     */
    public function findTagsForAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder
            ->innerJoin('listTag.toDoLists', 'list')
            ->andWhere('list.author = :user')
            ->setParameter('user', $user)
            ->orderBy('listTag.id');

        return $queryBuilder;
    }
}
