<?php
/**
 * Zgloszenie service.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\Zgloszenie;
use App\Repository\ZgloszenieRepository;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ZgloszenieService.
 */
class ZgloszenieService
{
    /**
     * Zgloszenie repository.
     *
     * @var \App\Repository\ZgloszenieRepository
     */
    private $zgloszenieRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * ZgloszenieService constructor.
     *
     * @param \App\Repository\ZgloszenieRepository          $zgloszenieRepository Zgloszenie repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator      Paginator
     */
    public function __construct(ZgloszenieRepository $zgloszenieRepository, PaginatorInterface $paginator)
    {
        $this->zgloszenieRepository = $zgloszenieRepository;
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
            $this->zgloszenieRepository->queryAll(),
            $page,
            ZgloszenieRepository::PAGINATOR_ITEMS_PER_PAGE
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
            $this->zgloszenieRepository->queryByAuthor($author),
            $page,
            ZgloszenieRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save zgloszenie.
     *
     * @param \App\Entity\Zgloszenie $zgloszenie Zgloszenie entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Zgloszenie $zgloszenie): void
    {
        $this->zgloszenieRepository->save($zgloszenie);
    }

    /**
     * Delete zgloszenie.
     *
     * @param \App\Entity\Zgloszenie $zgloszenie Zgloszenie entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Zgloszenie $zgloszenie): void
    {
        $this->zgloszenieRepository->delete($zgloszenie);
    }

    /**
     * Find zgloszenie by Id.
     *
     * @param int $id Zgloszenie Id
     *
     * @return \App\Entity\Zgloszenie|null Zgloszenie entity
     */
    public function findOneById(int $id): ?Zgloszenie
    {
        return $this->zgloszenieRepository->findOneById($id);
    }


    /**
     * Find zgloszenie by author
     * @param array $author
     *
     * @return Zgloszenie[]
     */
    public function findAuthorBy(array $author): array
    {
        return $this->zgloszenieRepository->findBy($author);
    }
}
