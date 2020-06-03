<?php
/**
 * ElementStatus fixture.
 */

namespace App\DataFixtures;

use App\Entity\ListElementStatus;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ElementStatusFixtures.
 */
class ElementStatusFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(2, 'elementStatuses', function ($i) {
            $status = new ListElementStatus();
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