<?php
/**
 * Category fixture.
 */

namespace App\DataFixtures;

use App\Entity\ListCategory;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ListCategoryFixtures.
 */
class ListCategoryFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'categories', function ($i) {
            $category = new ListCategory();
            $category->setTitle($this->faker->word);

            return $category;
        });

        $manager->flush();
    }
}