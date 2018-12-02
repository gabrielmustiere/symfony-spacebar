<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserFixture.
 */
class UserFixture extends BaseFixture
{
    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);

            return $user;
        });

        $manager->flush();
    }
}
