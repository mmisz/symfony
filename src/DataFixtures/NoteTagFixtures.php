<?php
/**
 * NoteTag fixtures.
 */

namespace App\DataFixtures;

use App\Entity\NoteTag;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class NoteTagFixtures.
 */
class NoteTagFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'tags', function ($i) {
            $tag = new NoteTag();
            $tag->setName($this->faker->word);
            $tag->addNote($this->getRandomReference('toDoLists'));
            $tag->addNote($this->getRandomReference('toDoLists'));
            $tag->addNote($this->getRandomReference('toDoLists'));
            $tag->addNote($this->getRandomReference('toDoLists'));
            $tag->addNote($this->getRandomReference('toDoLists'));
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
        return [ToDoFixtures::class];
    }
}