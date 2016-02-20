<?php

namespace AppBundle\Tests\Controller;


use AppBundle\DataFixtures\ORM\BlogArticleLoader;
use Liip\FunctionalTestBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    /**
     * @test
     */
    public function ブログ記事一覧が表示されること()
    {
        $this->loadFixtureFiles([
            '@AppBundle/Resources/fixtures/BlogArticle.yml'
        ]);

        $client = static::createClient();
        $crawler = $client->request('GET', '/blog/');

        $this->assertThat(
            $crawler->filter('li.blog-article')->count(),
            $this->equalTo(20)
        );
    }
}
