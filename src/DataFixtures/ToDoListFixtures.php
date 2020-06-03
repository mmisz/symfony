<?php
/**
 * Category fixture.
 */

namespace App\DataFixtures;

use App\Entity\ToDoList;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
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
            $toDoList->setStatus($this->getReference('statuses'));
            $toDoList->setDoneDate(null);
            $toDoList->setCategory($this->getRandomReference('categories'));
            return $toDoList;
        });

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return array(
            StatusFixtures::class,
        );
    }
}