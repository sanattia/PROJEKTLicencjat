<?php
/**
 * Registration service.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\UsersData;
use App\Repository\UserRepository;
use App\Repository\UsersDataRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * Class RegistrationService.
 */
class RegistrationService
{
    /**
     * Password encoder.
     */
    private UserPasswordEncoderInterface $passwordEncoder;

    /**
     * User repository.
     */
    private UserRepository $userRepository;


    /**
     * RegistrationService constructor.
     *
     * @param \App\Repository\UserRepository                                        $userRepository      User repository
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder    Password Encoder
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

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
     * Register.
     *
     * @param                       $data
     * @param \App\Entity\User      $user      User entity
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function register($data, User $user)
    {
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user, $data['password'])
        );
        $user->setRoles(['ROLE_USER']);

        $this->userRepository->save($user);
    }
}
