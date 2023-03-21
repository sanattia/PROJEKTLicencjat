<?php
/**
 * Trudnosc service.
 */

namespace App\Service;

use App\Entity\Trudnosc;
use App\Repository\TrudnoscRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TrudnoscService.
 */
class TrudnoscService
{
    /**
     * Trudnosc repository.
     *
     * @var \App\Repository\TrudnoscRepository
     */
    private $TrudnoscRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * TrudnoscService constructor.
     *
     * @param \App\Repository\TrudnoscRepository      $TrudnoscRepository Trudnosc repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator          Paginator
     */
    public function __construct(TrudnoscRepository $TrudnoscRepository, PaginatorInterface $paginator)
    {
        $this->TrudnoscRepository = $TrudnoscRepository;
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
            $this->TrudnoscRepository->queryAll(),
            $page,
            TrudnoscRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save Trudnosc.
     *
     * @param \App\Entity\Trudnosc $Trudnosc Trudnosc entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Trudnosc $Trudnosc): void
    {
        $this->TrudnoscRepository->save($Trudnosc);
    }

    /**
     * Delete Trudnosc.
     *
     * @param \App\Entity\Trudnosc $Trudnosc Trudnosc entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Trudnosc $Trudnosc): void
    {
        $this->TrudnoscRepository->delete($Trudnosc);
    }

    /**
     * Find Trudnosc by Id.
     *
     * @param int $id Trudnosc Id
     *
     * @return \App\Entity\Trudnosc|null Trudnosc entity
     */
    public function findOneById(int $id): ?Trudnosc
    {
        return $this->TrudnoscRepository->findOneById($id);
    }
}
