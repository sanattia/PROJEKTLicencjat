<?php
/**
 * Trasa fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Trasa;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TrasaFixtures.
 */
class TrasaFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(40, 'trasy', function ($i) {
            $trasa = new Trasa();

            switch ($i) {
                case 0:
                    $trasa->setName("Dzięgielów - Zamek z Cieszyna przez Mniszewo");
                    break;
                case 1:
                    $trasa->setName("Jasieniowa z Bażantowic");
                    break;
                case 2:
                    $trasa->setName("Jasieniowa z Dzięgielów - Zamek");
                    break;
                case 3:
                    $trasa->setName("Jasieniowa z Goleszowa");
                    break;
                case 4:
                    $trasa->setName("Schronisko PTTK Tuł z Jasieniowej");
                    break;
                case 5:
                    $trasa->setName("Schronisko PTTK Tuł z Goleszowa");
                    break;
                case 6:
                    $trasa->setName("Schronisko PTTK Tuł z Lesznej Górnej");
                    break;
                case 7:
                    $trasa->setName("Schronisko PTTK Tuł z Cisownicy");
                    break;
                case 8:
                    $trasa->setName("Schronisko PTTK Tuł z Ustronia");
                    break;
                case 9:
                    $trasa->setName("Mała Czantoria ze Schroniska PTTK Tuł");
                    break;
                case 10:
                    $trasa->setName("Mała Czantoria z Cisownicy");
                    break;
                case 11:
                    $trasa->setName("Góra Lipiec z Bystrzycy Górnej");
                    break;
                case 12:
                    $trasa->setName("Góra Lipiec z Zagórza Śląskiego");
                    break;
                case 13:
                    $trasa->setName("Cerekwica z Michałkowa");
                    break;
                case 14:
                    $trasa->setName("Bojanicka Struga z Bystrzycy Górnej");
                    break;
                case 15:
                    $trasa->setName("Michałkowa z Lubachowa");
                    break;
                case 16:
                    $trasa->setName("Glinno z Michałkowej");
                    break;
                case 17:
                    $trasa->setName("Glinno z Walimia");
                    break;
                case 18:
                    $trasa->setName("Glinno z Lutomii Górnej");
                    break;
                case 19:
                    $trasa->setName("Kroacka Studzienka z Lutomii Górnej");
                    break;
                case 20:
                    $trasa->setName("Cisowa Skała z Szaflar");
                    break;
                case 21:
                    $trasa->setName("Cisowa Skała z Nowego Targu");
                    break;
                case 22:
                    $trasa->setName("Nowa Biała z Dębna");
                    break;
                case 23:
                    $trasa->setName("Nowa Biała z Czarnej Góry");
                    break;
                case 24:
                    $trasa->setName("Wisła - Jawornik z Wisły Uzdrowiska");
                    break;
                case 25:
                    $trasa->setName("Schronisko PTTK Stożek z Mraznicy");
                    break;
                case 26:
                    $trasa->setName("Kozińce z Wisły Głębce");
                    break;
                case 27:
                    $trasa->setName("Wielka Zabawa z Soli");
                    break;
                case 28:
                    $trasa->setName("Koniaków z Istebnej");
                    break;
                case 29:
                    $trasa->setName(" Wisła Czarne z Wisła Malinka");
                    break;
                case 30:
                    $trasa->setName("Grojec z Żywca");
                    break;
                case 31:
                    $trasa->setName("Schronisko PTTK Luboń Wielki z Rabki");
                    break;
                case 32:
                    $trasa->setName("Przełęcz Glisne z Mszany Dolnej");
                    break;
                case 33:
                    $trasa->setName("Szczebel z Kasinki Małej");
                    break;
                case 34:
                    $trasa->setName("Zapadliska z Mszany Dolnej");
                    break;
                case 35:
                    $trasa->setName("Lubogoszcz z Mszany Dolnej");
                    break;
                case 36:
                    $trasa->setName("Ciecień z Wiśniowej");
                    break;
                case 37:
                    $trasa->setName("Grodzisko z Czesława");
                    break;
                case 38:
                    $trasa->setName("Kostrza z Szyk");
                    break;
                case 39:
                    $trasa->setName("Śnieżnica z Porąbki");
                    break;
            }
            $trasa->setPunktKoncowy("Przykładowy Punkt");
            $trasa->setPunktStartowy("Przykładowy Punkt");
            $trasa->setTrudnosc($this->getRandomReference('trudnosci'));
            $trasa->setRegion($this->getRandomReference('regiony'));
            $trasa->setPoints($this->faker->numberBetween(4,15));
            $trasa->setCzas($this->faker->dateTime);
            $trasa->setTest("admin");
            $trasa->setAuthor($this->getRandomReference('admins'));
            $trasa->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $trasa->setUpdatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(1, 4)
            );

            foreach ($tags as $tag) {
                $trasa->addTag($tag);
            }

            return $trasa;
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
        return [RegionFixtures::class, TrudnoscFixtures::class, TagFixtures::class, UserFixtures::class];
    }
}
