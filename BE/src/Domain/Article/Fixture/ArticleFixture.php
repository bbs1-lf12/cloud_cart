<?php

declare(strict_types=1);

namespace App\Domain\Article\Fixture;

use App\Domain\Article\Entity\Article;
use App\Domain\Article\Entity\Category;
use App\Domain\Article\Entity\Comment;
use App\Domain\Options\Entity\Options;
use App\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixture extends Fixture
{
    public const USER_QUANTITY = 10;
    public const ARTICLE_QUANTITY = 20;
    public const CATEGORY_QUANTITY = 3;
    public const COMMENT_PER_ARTICLE_QUANTITY = 20;

    public function load(ObjectManager $manager): void
    {
        $options = new Options();
        $options->setAppName('Sample');
        $options->setAppLogo(null);
        $options->setLowStockNotification(0);
        $manager->persist($options);

        $users = [];
        for ($i = 0; $i < self::USER_QUANTITY; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com');
            $user->setPassword('password');
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 0; $i < self::CATEGORY_QUANTITY; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $category->setDescription('Description ' . $i);
            $category->setIsEnabled(true);
            $manager->persist($category);
            $this->addReference(
                'category_' . $i,
                $category,
            );
        }

        for ($i = 0; $i < self::ARTICLE_QUANTITY; $i++) {
            $article = new Article();
            $article->setTitle('Article ' . $i);
            $article->setDescription('Description ' . $i);
            $article->setPriceInCents($i * 100);
            $article->setStock($i * 10);
            $article->setIsFeatured($i % 2 === 0);
            $article->setIsEnabled(true);
            $article->setCategory($this->getReference('category_' . ($i % self::CATEGORY_QUANTITY)));

            for ($j = 0; $j < self::COMMENT_PER_ARTICLE_QUANTITY; $j++) {
                $comment = new Comment();
                $comment->setContent('Comment ' . $j . ' ' . $article->getTitle());
                $comment->setArticle($article);
                $comment->setUser($users[$j % self::USER_QUANTITY]);
                $manager->persist($comment);
            }

            $manager->persist($article);
        }

        $manager->flush();
    }
}
