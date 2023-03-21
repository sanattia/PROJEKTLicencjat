<?php
/**
 * Odznaka service.
 */

namespace App\Service;

use App\Entity\Odznaka;
use App\Repository\OdznakaRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class OdznakaService.
 */
class OdznakaService
{
    /**
     * Odznaka repository.
     *
     * @var \App\Repository\OdznakaRepository
     */
    private $odznakaRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * OdznakaService constructor.
     *
     * @param \App\Repository\OdznakaRepository           $odznakaRepository Odznaka repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator     Paginator
     */
    public function __construct(OdznakaRepository $odznakaRepository, PaginatorInterface $paginator)
    {
        $this->odznakaRepository = $odznakaRepository;
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
            $this->odznakaRepository->queryAll(),
            $page,
            OdznakaRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find by title.
     *
     * @param string $title odznaka title
     *
     * @return \App\Entity\Odznaka|null Odznaka entity
     */
    public function findOneByTitle(string $title): ?Odznaka
    {
        return $this->odznakaRepository->findOneByTitle($title);
    }

    /**
     * Save odznaka.
     *
     * @param \App\Entity\Odznaka $odznaka Odznaka entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Odznaka $odznaka): void
    {
        $this->odznakaRepository->save($odznaka);
    }

    /**
     * Delete odznaka.
     *
     * @param \App\Entity\Odznaka $odznaka Odznaka entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Odznaka $odznaka): void
    {
        $this->odznakaRepository->delete($odznaka);
    }

    /**
     * Find by id.
     *
     * @param int $id Odznaka id
     *
     * @return Odznaka|null Odznaka entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Odznaka
    {
        return $this->odznakaRepository->findOneById($id);
    }

}
