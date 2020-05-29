<?php
/**
 * Category fixture.
 */

namespace App\DataFixtures;

use App\Entity\ToDoList;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ToDoListFixtures.
 */
class ToDoListFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(30, 'toDoLists', function ($i) {
            $toDoList = new ToDoList();
            $toDoList->setTitle($this->faker->word);
            $toDoList->setCreation($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $toDoList->setDone(0);
            $toDoList->setCategory($this->getRandomReference('categories'));
            return $toDoList;
        });

        $manager->flush();
    }
}