<?php
/**
 * ListTag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ListTag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class TagFixtures.
 */
class ListTagFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'tags', function ($i) {
            $tag = new ListTag();
            $tag->setName($this->faker->word);
            $tag->addToDoList($this->getRandomReference('toDoLists'));
            $tag->addToDoList($this->getRandomReference('toDoLists'));
            $tag->addToDoList($this->getRandomReference('toDoLists'));
            $tag->addToDoList($this->getRandomReference('toDoLists'));
            $tag->addToDoList($this->getRandomReference('toDoLists'));
            return $tag;
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