<?php

namespace App\Repository;

use App\Entity\Note;
use App\Entity\NoteCategory;
use App\Entity\NoteTag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Note|null find($id, $lockMode = null, $lockVersion = null)
 * @method Note|null findOneBy(array $criteria, array $orderBy = null)
 * @method Note[]    findAll()
 * @method Note[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoteRepository extends ServiceEntityRepository
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
     * NoteRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(\Doctrine\Common\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    /**
     * @param Note $note
     */
    public function save(Note $note): void
    {
        try {
            $this->_em->persist($note);
        } catch (ORMException $e) {
        }
        try {
            $this->_em->flush($note);
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
        }
    }
    /**
     * Delete record.
     *
     * @param \App\Entity\ListComment $listComment ListComment entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Note $note): void
    {
        $this->_em->remove($note);
        $this->_em->flush($note);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */

    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('note.creation', 'DESC');
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
        return $queryBuilder ?? $this->createQueryBuilder('note');
    }
    /**
     * Query tasks by author.
     *
     * @param \App\Entity\User $user User entity
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('note.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    public function queryByAuthorAndCategory(User $user, NoteCategory $category): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('note.author = :author AND note.category = :category')
            ->setParameter('category', $category)
            ->setParameter('author', $user)
        ;

        return $queryBuilder;
    }


    public function queryByAuthorAndTag(User $user, NoteTag $tag): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('note.author = :author')
            ->setParameter('author', $user)
            ->innerJoin('note.noteTags', 'note_tag')
            ->andWhere('note_tag = :tag')
            ->setParameter('tag', $tag)
        ;

        return $queryBuilder;
    }
    public function findByTag(NoteTag $tag)
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder
            ->innerJoin('note.noteTags', 'note_tag')
            ->andWhere('note_tag = :tag')
            ->setParameter('tag', $tag)
        ;

        return $queryBuilder;
    }
    // /**
    //  * @return Note[] Returns an array of Note objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Note
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
