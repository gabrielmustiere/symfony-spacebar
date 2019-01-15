<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class CommentFixture.
 */
class CommentFixture extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(100, 'main_comments', function () {
            $comment = new Comment();
            $comment->setContent(
                $this->faker->boolean ? $this->faker->paragraph : $this->faker->sentences(2, true)
            );

            $comment->setAuthorName($this->faker->name);
            $comment->setCreatedAt($this->faker->dateTimeBetween('-1 months', '-1 seconds'));
            $comment->setIsDeleted($this->faker->boolean(20));
            $comment->setArticle($this->getRandomReference('main_articles'));

            return $comment;
        });

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [ArticleFixture::class];
    }
}
