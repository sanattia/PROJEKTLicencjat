<?php
/**
 * Reset Password Request repository.
 */

namespace App\Repository;

use App\Entity\ResetPasswordRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

/**
 * @method ResetPasswordRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResetPasswordRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResetPasswordRequest[]    findAll()
 * @method ResetPasswordRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;
    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(): void
    {
        $this->_em->flush();
    }

    /**
     * ResetPasswordRequestRepository constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    /**
     * Task constructor.
     * @param object             $user        Result
     * @param \DateTimeInterface $expiresAt   Expires At
     * @param string             $selector    Selector
     * @param string             $hashedToken Hashed Token
     *
     * @return ResetPasswordRequest ResetPasswordRequest
     */
    public function createResetPasswordRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): ResetPasswordRequestInterface
    {
        return new ResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);
    }
}
