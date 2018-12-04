<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserFixture.
 */
class UserFixture extends BaseFixture
{
    /** @var UserPasswordEncoderInterface */
    private $userPasswordEncoder;

    /**
     * UserFixture constructor.
     *
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'password'));

            return $user;
        });

        $this->createMany(3, 'admin_users', function ($i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setFirstName($this->faker->firstName);
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, 'password'));
            $user->setRoles(['ROLE_ADMIN']);

            return $user;
        });

        $manager->flush();
    }
}
