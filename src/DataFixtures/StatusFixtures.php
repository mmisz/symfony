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
        $this->createMany(1, 'statuses', function ($i) {
            $status = new ListStatus();
            $status->setName('to do');
            $status->setName('done');

            return $status;
        });

        $this->createMany(1, 'statuses', function ($i) {
            $status = new ListElementStatus();
            $status->setName('to do');
            $status->setName('done');

            return $status;
        });

        $manager->flush();
    }
}