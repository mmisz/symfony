<?php
/**
 * ToDo fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Note;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ToDoFixture.
 */
class ToDoFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'notes', function ($i) {
            $toDoList = new Note();
            $toDoList->setTitle($this->faker->sentence);
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
        return [NoteCategoryFixtures::class, UserFixtures::class];
    }
}