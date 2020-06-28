<?php
/**
 * Status fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ListElementStatus;
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
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(2, 'statuses', function ($i) {
            $status = new ListStatus();
            if (0 == $i) {
                $status->setName('to do');
            }
            if (1 == $i) {
                $status->setName('done');
            }

            return $status;
        });

        $this->createMany(2, 'elementStatuses', function ($i) {
            $status = new ListElementStatus();
            if (0 == $i) {
                $status->setName('to do');
            }
            if (1 == $i) {
                $status->setName('done');
            }

            return $status;
        });

        $manager->flush();
    }
}
