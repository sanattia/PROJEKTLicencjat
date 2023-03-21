<?php
/** ResetPassword service.
 */

namespace App\Service;

use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use App\Repository\ResetPasswordRequestRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;

/** Class ResetPasswordServiceService.
 */
class ResetPasswordService
{
    private ResetPasswordRequestRepository $passwordRequestRepository;

/** @param ResetPasswordRequestRepository $passwordRequestRepository
*/
    public function __construct(ResetPasswordRequestRepository $passwordRequestRepository)
    {
        $this->passwordRequestRepository = $passwordRequestRepository;
    }

/** @throws ORMException
* @throws OptimisticLockException
*/
    public function save(): void
    {
        $this->passwordRequestRepository->save();
    }
}
