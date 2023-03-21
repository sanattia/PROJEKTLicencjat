<?php
/**
 * Trudnosc repository.
 */

namespace App\Repository;

use App\Entity\Trudnosc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class TrudnoscRepository.
 *
 * @method Trudnosc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trudnosc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trudnosc[]    findAll()
 * @method Trudnosc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrudnoscRepository extends ServiceEntityRepository
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
     * TrudnoscRepository constructor.
     *
     * @param \Doctrine\Common\Persistence\ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trudnosc::class);
    }

    /**
     * Query all records.
     *
     * @return \Doctrine\ORM\QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('trudnosc.name', 'DESC');
    }

    /**
     * Save record.
     *
     * @param \App\Entity\Trudnosc $trudnosc Trudnosc entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Trudnosc $trudnosc): void
    {
        $this->_em->persist($trudnosc);
        $this->_em->flush();
    }

    /**
     * Delete record.
     *
     * @param \App\Entity\Trudnosc $trudnosc Trudnosc entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Trudnosc $trudnosc): void
    {
        $this->_em->remove($trudnosc);
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
        return $queryBuilder ?? $this->createQueryBuilder('trudnosc');
    }
}
