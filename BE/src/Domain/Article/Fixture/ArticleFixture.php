<?php

declare(strict_types=1);

namespace App\Domain\Article\Fixture;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{
    const ARTICLE_QUANTITY = 20;
    const CATEGORY_QUANTITY = 3;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::CATEGORY_QUANTITY; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $category->setDescription('Description ' . $i);
            $category->setIsEnabled(true);
            $manager->persist($category);
            $this->addReference('category_' . $i, $category);
        }

        for ($i = 0; $i < self::ARTICLE_QUANTITY; $i++) {
            $article = new Article();
            $article->setTitle('Article ' . $i);
            $article->setDescription('Description ' . $i);
            $article->setPriceInCents($i * 100);
            $article->setStock($i * 10);
            $article->setIsFeatured($i % 2 === 0);
            $article->setIsEnabled(true);
            $article->setScore($i * 0.1);
            $article->setCategory($this->getReference('category_' . ($i % self::CATEGORY_QUANTITY)));
            $manager->persist($article);
        }

        $manager->flush();
    }
}
