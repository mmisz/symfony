<?php

namespace App\Repository;

use App\Entity\ListCategory;
use App\Entity\ToDoList;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
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
    public function save(ToDoList $toDoList): void
    {
        $this->_em->persist($toDoList);
        $this->_em->flush($toDoList);
    }
    /**
     * Delete record.
     *
     * @param \App\Entity\ListComment $listComment ListComment entity
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
     * @param \App\Entity\User $user User entity
     *
     * @param ListCategory $category
     * @return \Doctrine\ORM\QueryBuilder Query builder
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
}
