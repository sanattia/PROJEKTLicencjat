<?php
/**
 * Odznaka repository.
 */

namespace App\Repository;

use App\Entity\Odznaka;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class OdznakaRepository.
 *
 * @method Odznaka|null find($id, $lockMode = null, $lockVersion = null)
 * @method Odznaka|null findOneBy(array $criteria, array $orderBy = null)
 * @method Odznaka[]    findAll()
 * @method Odznaka[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OdznakaRepository extends ServiceEntityRepository
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
     * OdznakaRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Odznaka::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('odznaka.title', 'DESC');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Odznaka $odznaka Odznaka entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Odznaka $odznaka): void
    {
        $this->_em->persist($odznaka);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Odznaka $odznaka Odznaka entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Odznaka $odznaka): void
    {
        $this->_em->remove($odznaka);
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
        return $queryBuilder ?? $this->createQueryBuilder('odznaka');
    }

}
