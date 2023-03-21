<?php
/**
 * Trudnosc fixture.
 */

namespace App\DataFixtures;

use App\Entity\Trudnosc;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TrudnoscFixtures.
 */
class TrudnoscFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(4, 'trudnosci', function ($i) {
            $trudnosc = new Trudnosc();
            switch ($i) {
                case 0:
                    $trudnosc->setName("*");
                    break;
                case 1:
                    $trudnosc->setName("**");
                    break;
                case 2:
                    $trudnosc->setName("***");
                    break;
                case 3:
                    $trudnosc->setName("****");
                    break;
            }

            return $trudnosc;
        });

        $manager->flush();
    }
}

