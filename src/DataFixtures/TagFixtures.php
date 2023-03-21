<?php
/**
 * Tag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TagFixtures.
 */
class TagFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(9, 'tags', function ($i) {
            $tag = new Tag();
            switch ($i) {
                case 0:
                    $tag->setTitle("kapliczka");
                    break;
                case 1:
                    $tag->setTitle("schronisko");
                    break;
                case 2:
                    $tag->setTitle("rzeka");
                    break;
                case 3:
                    $tag->setTitle("bacówka");
                    break;
                case 4:
                    $tag->setTitle("jaskinia");
                    break;
                case 5:
                    $tag->setTitle("jezioro");
                    break;
                case 6:
                    $tag->setTitle("trasa edukacyjna");
                    break;
                case 7:
                    $tag->setTitle("punkt widokowy");
                    break;
                case 8:
                    $tag->setTitle("wodospad");
                    break;
                case 9:
                    $tag->setTitle("pomnik przyrody");
                    break;
            }


            return $tag;
        });

        $manager->flush();
    }
}
