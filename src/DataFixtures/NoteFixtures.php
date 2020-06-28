<?php
/**
 * ToDo fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Note;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ToDoFixture.
 */
class NoteFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * data loader for note.
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'notes', function ($i) {
            $note = new Note();
            $note->setTitle($this->faker->word);
            $note->setContent($this->faker->sentence);
            $note->setCreation($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $note->setLastUpdate($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $note->setCategory($this->getRandomReference('noteCategories'));
            $note->setAuthor($this->getRandomReference('users'));

            return $note;
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
