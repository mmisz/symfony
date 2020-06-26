<?php
/**
 * Status fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ListStatus;
use App\Entity\ListElementStatus;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            if ($i == 0) {
                $status->setName('to do');
            }
            if ($i == 1) {
                $status->setName('done');
            }

            return $status;
        });

        $this->createMany(2, 'elementStatuses', function ($i) {
            $status = new ListElementStatus();
            if ($i == 0) {
                $status->setName('to do');
            }
            if ($i == 1) {
                $status->setName('done');
            }

            return $status;
        });

        $manager->flush();
    }
}
