<?php
/**
 * Avatar service.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\Avatar;
use App\Repository\AvatarRepository;
use App\Repository\WpisRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class AvatarService.
 */
class AvatarService
{
    /**
     * Avatar repository.
     *
     * @var \App\Repository\AvatarRepository
     */
    private $avatarRepository;

    /**
     * Paginator.
     *
     * @var \Knp\Component\Pager\PaginatorInterface
     */
    private $paginator;

    /**
     * AvatarService constructor.
     *
     * @param \App\Repository\AvatarRepository          $avatarRepository Avatar repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator      Paginator
     */
    public function __construct(AvatarRepository $avatarRepository, PaginatorInterface $paginator)
    {
        $this->avatarRepository = $avatarRepository;
        $this->paginator = $paginator;
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
            $this->avatarRepository->queryByAuthor($author),
            $page,
            AvatarRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }



    /**
     * Save avatar.
     *
     * @param \App\Entity\Avatar $avatar Avatar entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Avatar $avatar): void
    {
        $this->avatarRepository->save($avatar);
    }

    /**
     * Delete avatar.
     *
     * @param \App\Entity\Avatar $avatar Avatar entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Avatar $avatar): void
    {
        $this->avatarRepository->delete($avatar);
    }

    /**
     * Find avatar by Id.
     *
     * @param int $id Avatar Id
     *
     * @return \App\Entity\Avatar|null Avatar entity
     */
    public function findOneById(int $id): ?Avatar
    {
        return $this->avatarRepository->findOneById($id);
    }

    /**
     * Find avatar by author
     * @param array $author
     *
     * @return Avatar[]
     */
    public function findAuthorBy(array $author): array
    {
        return $this->avatarRepository->findBy($author);
    }
}
