<?php
/**
 * Wpis fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Wpis;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class WpisFixtures.
 */
class WpisFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'wpisy', function ($i) {
            $wpis = new Wpis();

            $wpis->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $wpis->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $wpis->setImageName("noPic.png");
            $wpis->setTrasa($this->getRandomReference('trasy'));
            $wpis->setAuthor($this->getRandomReference('users'));


            return $wpis;
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
        return [TrasaFixtures::class, UserFixtures::class];
    }
}
