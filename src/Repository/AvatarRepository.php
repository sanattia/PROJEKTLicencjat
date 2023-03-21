<?php

namespace App\Repository;

use App\Entity\Avatar;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Avatar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avatar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avatar[]    findAll()
 * @method Avatar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvatarRepository extends ServiceEntityRepository
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
    public const PAGINATOR_ITEMS_PER_PAGE = 10;


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avatar::class);
    }


    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('avatar.author', 'DESC');
    }

    /**
     *
     * Query tasks by author.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('avatar.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('avatar');
    }


    /**
     * Save record.
     *
     * @param \App\Entity\Avatar $avatar Avatar entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Avatar $avatar): void
    {
        $this->_em->persist($avatar);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Avatar $avatar Avatar entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Avatar $avatar): void
    {
        $this->_em->remove($avatar);
        $this->_em->flush();
    }
}
