<?php
/**
 * Zgloszenie fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Zgloszenie;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ZgloszenieFixtures.
 */
class ZgloszenieFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'zgloszenia', function ($i) {
            $zgloszenie = new Zgloszenie();
            $zgloszenie->setOdznaka($this->faker->word);
            $zgloszenie->setKomentarz($this->faker->sentence);
            $zgloszenie->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $zgloszenie->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $zgloszenie->setAuthor($this->getRandomReference('users'));


            return $zgloszenie;
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
        return [UserFixtures::class];
    }
}
