<?php

namespace AppBundle\DataFixtures\ORM;


use Hautelook\AliceBundle\Doctrine\DataFixtures\AbstractLoader;

class BlogArticleLoader extends AbstractLoader
{
    public function getFixtures()
    {
        return array(
            __DIR__.'/../../Resources/fixtures/BlogArticle.yml',
        );
    }

}
