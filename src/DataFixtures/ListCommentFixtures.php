<?php
/**
 * ListComment fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ListComment;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class ListCommentFixtures.
 */
class ListCommentFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(170, 'comments', function ($i) {
            $comment = new ListComment();
            $comment->setContent($this->faker->sentence);
            $comment->setToDoList($this->getRandomReference('toDoLists'));
            $comment->setCreation($this->faker->dateTimeBetween('-100 days', '-1 days'));

            return $comment;
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