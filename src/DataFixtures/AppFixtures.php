<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $article = new Article();
        $article->setTitre('Article 1')
            ->setContenu('lorem')
            ->setDateArticle('27/08/23');

        $manager->flush();
    }
}
