<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * User repository.
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * Paginator.
     *
     * @var PaginatorInterface
     */
    private $paginator;


    /**
     * Save user.
     *
     * @param \App\Entity\User $user User entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * UserService constructor.
     *
     * @param UserRepository $userRepository User repository
     * @param PaginatorInterface $paginator          Paginator
     */
    public function __construct(UserRepository $userRepository, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
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
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Find user by email
     * @param array $credentials
     *
     * @return User
     */
    public function findOneBy(array $credentials): User
    {
        return $this->userRepository->findOneBy($credentials);
    }


    /**
     * Find user.
     *
     * @param string $email Email
     *
     * @return \App\Entity\User|null
     */
    public function findOneByEmail(string $email): ?User
    {
        return $this->userRepository->findOneByEmail(['email' => $email]);
    }
}
