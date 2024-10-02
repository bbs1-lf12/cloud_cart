<?php

declare(strict_types=1);

namespace App\Domain\Article\Fixture;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\Entity\Category;
use App\Domain\Article\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{
    public const ARTICLE_QUANTITY = 20;
    public const CATEGORY_QUANTITY = 3;
    public const COMMENT_PER_ARTICLE_QUANTITY = 20;

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

            for ($j = 0; $j < self::COMMENT_PER_ARTICLE_QUANTITY; $j++) {
                $comment = new Comment();
                $comment->setContent('Comment ' . $j . ' ' . $article->getTitle());
                $comment->setArticle($article);
                $manager->persist($comment);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }
}
