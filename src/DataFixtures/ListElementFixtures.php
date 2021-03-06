<?php
/**
 * ListElement fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ListElement;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ListElementFixtures.
 */
class ListElementFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(150, 'elements', function ($i) {
            $element = new ListElement();
            $element->setContent($this->faker->sentence);
            $element->setStatus($this->getRandomReference('elementStatuses'));
            $element->setToDoList($this->getRandomReference('toDoLists'));
            $element->setCreation($this->faker->dateTimeBetween('-100 days', '-1 days'));

            return $element;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [StatusFixtures::class, ToDoListFixtures::class];
    }
}
