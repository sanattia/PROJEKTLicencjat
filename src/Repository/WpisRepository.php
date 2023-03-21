<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Wpis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Wpis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Wpis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Wpis[]    findAll()
 * @method Wpis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WpisRepository extends ServiceEntityRepository
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

    /**
     * WpisRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Wpis::class);
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
                'partial wpis.{id, createdAt, updatedAt, imageName, author}',
                'partial trasa.{id, name, points, region, trudnosc, czas, punktStartowy, punktKoncowy}',
                'partial trudnosc.{id, name}',
                'partial region.{id, name}'
            )
            ->join('wpis.trasa', 'trasa')
            ->leftJoin('trasa.trudnosc', 'trudnosc')
            ->leftJoin('trasa.region', 'region')
            ->orderBy('wpis.updatedAt', 'DESC');
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

        $queryBuilder->andWhere('wpis.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * Query tasks by points.
     *
     * @param User $user User entity
     *
     * @return QueryBuilder Query builder
     */
    public function queryByPoints(User $user): QueryBuilder{
        $queryBuilder = $this->queryAll();
        $queryBuilder->select('sum(trasa.points) as pointsCount')->andWhere('wpis.author = :author')
            ->setParameter('author', $user)->distinct()
            ->getQuery()
            ->getOneOrNullResult();

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Wpis $wpis Wpis entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Wpis $wpis): void
    {
        $this->_em->persist($wpis);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Wpis $wpis Wpis entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Wpis $wpis): void
    {
        $this->_em->remove($wpis);
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
        return $queryBuilder ?? $this->createQueryBuilder('wpis');
    }
}
