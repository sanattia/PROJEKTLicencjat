<?php
/**
 * Region repository.
 */

namespace App\Repository;

use App\Entity\Region;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class RegionRepository.
 *
 * @method Region|null find($id, $lockMode = null, $lockVersion = null)
 * @method Region|null findOneBy(array $criteria, array $orderBy = null)
 * @method Region[]    findAll()
 * @method Region[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegionRepository extends ServiceEntityRepository
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
     * RegionRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Region::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('region.name', 'DESC');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Region $region Region entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Region $region): void
    {
        $this->_em->persist($region);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Region $region Region entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Region $region): void
    {
        $this->_em->remove($region);
        $this->_em->flush();
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
        return $queryBuilder ?? $this->createQueryBuilder('region');
    }
}
