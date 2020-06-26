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
        $this->createMany(10, 'noteTags', function ($i) {
            $tag = new NoteTag();
            $tag->setName($this->faker->word);
            $tag->addNote($this->getRandomReference('notes'));
            $tag->addNote($this->getRandomReference('notes'));
            $tag->addNote($this->getRandomReference('notes'));
            $tag->addNote($this->getRandomReference('notes'));
            $tag->addNote($this->getRandomReference('notes'));
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
        return [NoteFixtures::class];
    }
}