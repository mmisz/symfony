<?php
/**
 * ListElementFixtures fixtures.
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
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(70, 'listElements', function ($i) {
            $listElement = new ListElement();
            $listElement->setContent($this->faker->sentence);
            $listElement->setDone(0);
            $listElement->setToDoList($this->getRandomReference('toDoLists'));

            return $listElement;
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
        return [ToDoListFixtures::class];
    }
}