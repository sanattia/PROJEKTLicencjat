<?php
/**
 * Wpis service.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\Wpis;
use App\Repository\WpisRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class WpisService.
 */
class WpisService
{
    /**
     * Wpis repository.
     *
     * @var \App\Repository\WpisRepository
     */
    private $wpisRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * WpisService constructor.
     *
     * @param \App\Repository\WpisRepository          $wpisRepository Wpis repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator      Paginator
     */
    public function __construct(WpisRepository $wpisRepository, PaginatorInterface $paginator)
    {
        $this->wpisRepository = $wpisRepository;
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
            $this->wpisRepository->queryAll(),
            $page,
            WpisRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Get paginated list.
     *
     * @param int  $page   Page number
     * @param User $author Author
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $author): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->wpisRepository->queryByAuthor($author),
            $page,
            WpisRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Count points.
     *
     * @param User $author Author
     * @return QueryBuilder
     *
     */
    public function countPoints(User $author): QueryBuilder
    {
        return $this->wpisRepository->queryByPoints($author);
    }

    /**
     * Save wpis.
     *
     * @param \App\Entity\Wpis $wpis Wpis entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Wpis $wpis): void
    {
        $this->wpisRepository->save($wpis);
    }

    /**
     * Delete wpis.
     *
     * @param \App\Entity\Wpis $wpis Wpis entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Wpis $wpis): void
    {
        $this->wpisRepository->delete($wpis);
    }

    /**
     * Find wpis by Id.
     *
     * @param int $id Wpis Id
     *
     * @return \App\Entity\Wpis|null Wpis entity
     */
    public function findOneById(int $id): ?Wpis
    {
        return $this->wpisRepository->findOneById($id);
    }

    /**
     * Find wpis by trasa
     * @param array $trasa
     *
     * @return Wpis[]
     */
    public function findBy(array $trasa): array
    {
        return $this->wpisRepository->findBy($trasa);
    }

    /**
     * Find wpis by author
     * @param array $author
     *
     * @return Wpis[]
     */
    public function findAuthorBy(array $author): array
    {
        return $this->wpisRepository->findBy($author);
    }
}
