<?php
/**
 * App fixtures.
 */

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures.
 */
class AppFixtures extends Fixture
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $manager->flush();
    }
}
