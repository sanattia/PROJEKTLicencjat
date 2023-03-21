<?php
/**
 * Odznaka fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Odznaka;
use Doctrine\Persistence\ObjectManager;

/**
 * Class OdznakaFixtures.
 */
class OdznakaFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(8, 'odznaki', function ($i) {
            $odznaka = new Odznaka();
            switch ($i) {
                case 0:
                    $odznaka->setTitle('Popularna');
                    break;
                case 1:
                    $odznaka->setTitle('Mała brązowa');
                    break;
                case 2:
                    $odznaka->setTitle('Mała srebrna');
                    break;
                case 3:
                    $odznaka->setTitle('Mała złota');
                    break;
                case 4:
                    $odznaka->setTitle('Duża brązowa');
                    break;
                case 5:
                    $odznaka->setTitle('Duża srebrna');
                    break;
                case 6:
                    $odznaka->setTitle('Duża złota');
                    break;
                case 7:
                    $odznaka->setTitle('Za wytrwałość');
                    break;
            }
            return $odznaka;
        });

        $manager->flush();
    }
}