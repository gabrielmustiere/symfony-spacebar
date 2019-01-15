<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

/**
 * Class ArticleControllerTest.
 */
class ArticleControllerTest extends PantherTestCase
{
    public function testHomepage()
    {
        $client = static::createPantherClient();
        $crawler = $client->request('GET', '/');

        $this->assertTrue(1 === $crawler->filter('#spacebar')->count());
    }
}
