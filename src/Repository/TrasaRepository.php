<?php

namespace App\Repository;

use App\Entity\Region;
use App\Entity\Tag;
use App\Entity\Trasa;
use App\Entity\Trudnosc;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Trasa|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trasa|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trasa[]    findAll()
 * @method Trasa[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrasaRepository extends ServiceEntityRepository
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
     * Items per page.
     *
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @constant int
     */
    public const PAGINATOR_ITEMS_PER_PAGE_MINI = 3;

    /**
     * TrasaRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trasa::class);
    }


    /**
     * Query all records.
     *
     * @param array<string, object> $filters Filters
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(array $filters): QueryBuilder
    {
        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial trasa.{id, createdAt, updatedAt, name, czas, points, adres, punktStartowy, punktKoncowy, imageName}',
                'partial trudnosc.{id, name}',
                'partial region.{id, name}',
                'partial tags.{id, title}'
            )
            ->join('trasa.trudnosc', 'trudnosc')
            ->leftJoin('trasa.region', 'region')
            ->leftJoin('trasa.tags', 'tags')
            ->orderBy('trasa.updatedAt', 'DESC');
        return $this->applyFiltersToList($queryBuilder, $filters);
    }


    /**
     * Query all records.
     *
     * @param array<string, object> $filters Filters
     * @param User $user User entity
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAllByAuthor(array $filters, User $user): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);

        $queryBuilder->andWhere('trasa.author = :author')
            ->setParameter('author', $user);

        return $queryBuilder;
    }

    /**
     * Query all records.
     *
     * @param array<string, object> $filters Filters
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAllByAdmin(array $filters): QueryBuilder
    {
        $queryBuilder = $this->queryAll($filters);

        $queryBuilder->andWhere('trasa.test = :testValue')
            ->setParameter('testValue', 'admin');

        return $queryBuilder;
    }

    /**
     * Query search records.
     *
     * @param array<string, object> $filters Filters
     * @param $dane
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function querySearch(array $filters, $dane): QueryBuilder
    {


        $queryBuilder = $this->getOrCreateQueryBuilder()
            ->select(
                'partial trasa.{id, createdAt, updatedAt, name, czas, points, adres, punktStartowy, punktKoncowy, imageName}',
                'partial trudnosc.{id, name}',
                'partial region.{id, name}',
                'partial tags.{id, title}'
            )
            ->join('trasa.trudnosc', 'trudnosc')
            ->leftJoin('trasa.region', 'region')
            ->leftJoin('trasa.tags', 'tags')
            ->orderBy('trasa.updatedAt', 'DESC')
            ->andWhere('trasa.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$dane.'%')
            ->andWhere('trasa.test LIKE :testValue')
            ->setParameter('testValue', 'admin')
        ;
        return $this->applyFiltersToList($queryBuilder, $filters);
    }


    /**
     * Apply filters to paginated list.
     *
     * @param QueryBuilder          $queryBuilder Query builder
     * @param array<string, object> $filters      Filters array
     *
     * @return QueryBuilder Query builder
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, array $filters = []): QueryBuilder
    {
        if (isset($filters['region']) && $filters['region'] instanceof Region) {
            $queryBuilder->andWhere('region = :region')
                ->setParameter('region', $filters['region']);
        }
        if (isset($filters['trudnosc']) && $filters['trudnosc'] instanceof Trudnosc) {
            $queryBuilder->andWhere('trudnosc = :trudnosc')
                ->setParameter('trudnosc', $filters['trudnosc']);
        }

        if (isset($filters['tag']) && $filters['tag'] instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters['tag']);
        }
        if ((isset($filters['region']) && $filters['region'] instanceof Region) && (isset($filters['trudnosc']) && $filters['trudnosc'] instanceof Trudnosc) && (isset($filters['tag']) && $filters['tag'] instanceof Tag)) {
            $queryBuilder->andWhere('region = :region')
                ->andWhere('trudnosc = :trudnosc')
                ->andWhere('tags IN (:tag)')
                ->setParameter('trudnosc', $filters['trudnosc'])
                ->setParameter('tag', $filters['tag'])
                ->setParameter('region', $filters['region']);
        }

        return $queryBuilder;
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Trasa $trasa Trasa entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Trasa $trasa): void
    {
        $this->_em->persist($trasa);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Trasa $trasa Trasa entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Trasa $trasa): void
    {
        $this->_em->remove($trasa);
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
        return $queryBuilder ?? $this->createQueryBuilder('trasa');
    }
}
