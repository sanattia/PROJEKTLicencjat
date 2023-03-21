<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Zgloszenie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Zgloszenie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zgloszenie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zgloszenie[]    findAll()
 * @method Zgloszenie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZgloszenieRepository extends ServiceEntityRepository
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
        parent::__construct($registry, Zgloszenie::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->select(
                'partial zgloszenie.{id, createdAt, updatedAt, odznaka, author, komentarz}'
            )
            ->orderBy('zgloszenie.updatedAt', 'DESC');
    }
    /**
     * Query tasks by author.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByAuthor(User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll();

        $queryBuilder->andWhere('zgloszenie.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Zgloszenie $zgloszenie Zgloszenie entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Zgloszenie $zgloszenie): void
    {
        $this->_em->persist($zgloszenie);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Zgloszenie $zgloszenie Zgloszenie entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Zgloszenie $zgloszenie): void
    {
        $this->_em->remove($zgloszenie);
        $this->_em->flush();
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
        return $queryBuilder ?? $this->createQueryBuilder('zgloszenie');
    }

}
