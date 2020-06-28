<?php
/**
 * NoteCategory fixture.
 */

namespace App\DataFixtures;

use App\Entity\NoteCategory;
use Doctrine\Persistence\ObjectManager;

/**
 * Class NoteCategoryFixtures.
 */
class NoteCategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'noteCategories', function ($i) {
            $category = new NoteCategory();
            $category->setTitle($this->faker->word);

            return $category;
        });

        $manager->flush();
    }
}
