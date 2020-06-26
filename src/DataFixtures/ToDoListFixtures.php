<?php
/**
 * ToDo fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ToDoList;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ToDoFixture.
 */
class ToDoListFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'toDoLists', function ($i) {
            $toDoList = new ToDoList();
            $toDoList->setTitle($this->faker->sentence);
            $toDoList->setStatus('to do');
            $toDoList->setCreation($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $toDoList->setCategory($this->getRandomReference('categories'));
            $toDoList->setAuthor($this->getRandomReference('users'));

            return $toDoList;
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
        return [ListCategoryFixtures::class, UserFixtures::class];
    }
}