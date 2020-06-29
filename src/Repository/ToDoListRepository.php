<?php

namespace App\Repository;

use App\Entity\ListCategory;
use App\Entity\ListTag;
use App\Entity\ToDoList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ToDoListRepository.
 *
 * @method ToDoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoList[]    findAll()
 * @method ToDoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoListRepository extends ServiceEntityRepository
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
     * ToDoListRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDoList::class);
    }

    /**
     * Save record.
     *
     * @param ToDoList $toDoList
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ToDoList $toDoList): void
    {
        $this->_em->persist($toDoList);
        $this->_em->flush($toDoList);
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\ToDoList $toDoList ListComment entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(ToDoList $toDoList): void
    {
        $this->_em->remove($toDoList);
        $this->_em->flush($toDoList);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select('toDoList', 'category')
            ->join('toDoList.category', 'category')
            ->orderBy('toDoList.creation', 'DESC');
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
        return $queryBuilder ?? $this->createQueryBuilder('toDoList');
    }

    /**
     * Query tasks by author.
     *
     * @param User $user
     * @param ListCategory $category
     * @return QueryBuilder
     */
    public function queryByAuthorAndCategory(User $user, ListCategory $category): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('toDoList.author = :author AND toDoList.category = :category')
            ->setParameter('category', $category)
            ->setParameter('author', $user)
        ;

        return $queryBuilder;
    }

    /**
     * Query tasks by author and tag.
     *
     * @param User $user
     * @param ListTag $tag
     * @return QueryBuilder
     */
    public function queryByAuthorAndTag(User $user, ListTag $tag): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('toDoList.author = :author')
            ->setParameter('author', $user)
            ->innerJoin('toDoList.listTag', 'list_tag')
            ->andWhere('list_tag = :tag')
            ->setParameter('tag', $tag);

        return $queryBuilder;
    }

    /**
     * find tasks by tag.
     *
     * @param ListTag $tag
     * @return QueryBuilder
     */
    public function findByTag(ListTag $tag): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder
            ->innerJoin('toDoList.listTag', 'list_tag')
            ->andWhere('list_tag = :tag')
            ->setParameter('tag', $tag);

        return $queryBuilder;
    }

    /**
     * query by author.
     *
     * @param User $user
     * @return QueryBuilder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('toDoList.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }
}
