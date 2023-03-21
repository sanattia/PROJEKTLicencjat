<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Password encoder.
     *
     * @var \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     *
     * @param \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $passwordEncoder Password encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setRoles([User::ROLE_USER]);
            $user->setUsername(sprintf('user%d', $i));
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'user1234'
                )
            );
            $odznaki = $this->getRandomReferences(
                'odznaki',
                $this->faker->numberBetween(0, 7)
            );

            foreach ($odznaki as $odznaka) {
                $user->addOdznaka($odznaka);
            }
            return $user;
        });

        $this->createMany(3, 'admins', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setRoles([User::ROLE_USER, User::ROLE_ADMIN]);
            $user->setUsername(sprintf('admin%d', $i));
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'admin1234'
                )
            );
            $odznaki = $this->getRandomReferences(
                'odznaki',
                $this->faker->numberBetween(0, 5)
            );

            foreach ($odznaki as $odznaka) {
                $user->addOdznaka($odznaka);
            }
            return $user;
        });

        $this->createMany(1, 'normal', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('agnieszka.moryc@student.uj.edu.pl', $i));
            $user->setRoles([User::ROLE_USER, User::ROLE_ADMIN]);
            $user->setUsername(sprintf('san%d', $i));
            $user->setPassword(
                $this->passwordEncoder->encodePassword(
                    $user,
                    'admin1234'
                )
            );
            $odznaki = $this->getRandomReferences(
                'odznaki',
                $this->faker->numberBetween(0, 5)
            );

            foreach ($odznaki as $odznaka) {
                $user->addOdznaka($odznaka);
            }

            return $user;
        });

        $manager->flush();

    }
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array<class-string<FixtureInterface>>
     */
    public function getDependencies(): array
    {
        return [OdznakaFixtures::class];
    }
}
