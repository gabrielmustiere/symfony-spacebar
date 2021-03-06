<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class TagFixture.
 */
class TagFixture extends BaseFixture
{
    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10, 'main_tags', function () {
            $tag = new Tag();
            $tag->setName($this->faker->realText(20));

            return $tag;
        });

        $manager->flush();
    }
}
