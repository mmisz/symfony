<?php
/**
 * Status fixture.
 */

namespace App\DataFixtures;

use App\Entity\ListStatus;
use Doctrine\Persistence\ObjectManager;

/**
 * Class StatusFixtures.
 */
class StatusFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(2, 'statuses', function ($i) {
            $status = new ListStatus();
            if($i==0){
                $status->setName("to do");
            }
            else{
                $status->setName("done");
            }
            return $status;
        });

        $manager->flush();
    }
}