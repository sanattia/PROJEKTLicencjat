<?php
/**
 * Region fixture.
 */

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Persistence\ObjectManager;

/**
 * Class RegionFixtures.
 */
class RegionFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(6, 'regiony', function ($i) {
            $region = new Region();
            switch ($i) {
                case 0:
                    $region->setName("Tatry");
                    break;
                case 1:
                    $region->setName("Beskidy Zachodnie");
                    break;
                case 2:
                    $region->setName("Beskidy Wschodnie");
                    break;
                case 3:
                    $region->setName("Sudety");
                    break;
                case 4:
                    $region->setName("Góry Świętokrzyskie");
                    break;
                case 5:
                    $region->setName("Tereny zagraniczne");
                    break;

            }


            return $region;
        });

        $manager->flush();
    }
}

