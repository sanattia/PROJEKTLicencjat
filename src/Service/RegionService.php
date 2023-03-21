<?php
/**
 * Region service.
 */

namespace App\Service;

use App\Entity\Region;
use App\Repository\RegionRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RegionService.
 */
class RegionService
{
    /**
     * Region repository.
     *
     * @var \App\Repository\RegionRepository
     */
    private $RegionRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * RegionService constructor.
     *
     * @param \App\Repository\RegionRepository      $RegionRepository Region repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     */
    public function __construct(RegionRepository $RegionRepository, PaginatorInterface $paginator)
    {
        $this->RegionRepository = $RegionRepository;
        $this->paginator = $paginator;
    }

    /**
     * Create paginated list.
     *
     * @param int $page Page number
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->RegionRepository->queryAll(),
            $page,
            RegionRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save Region.
     *
     * @param \App\Entity\Region $Region Region entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Region $Region): void
    {
        $this->RegionRepository->save($Region);
    }

    /**
     * Delete Region.
     *
     * @param \App\Entity\Region $Region Region entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Region $Region): void
    {
        $this->RegionRepository->delete($Region);
    }

    /**
     * Find Region by Id.
     *
     * @param int $id Region Id
     *
     * @return \App\Entity\Region|null Region entity
     */
    public function findOneById(int $id): ?Region
    {
        return $this->RegionRepository->findOneById($id);
    }
}
