<?php

declare(strict_types=1);

namespace App\Domain\Article\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\User\Entity\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: 'App\Domain\Article\Repository\ScoreRepository')]
class Score extends AbstractEntity
{
    #[Column(type: 'integer')]
    private int $score;
    #[ManyToOne(targetEntity: Article::class, inversedBy: 'scores')]
    private Article $article;
    #[ManyToOne(targetEntity: Article::class, inversedBy: 'scores')]
    private User $user;

    public function getScore(): int
    {
        return $this->score;
    }

    public function setScore(int $score): void
    {
        $this->score = $score;
    }

    public function getArticle(): Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}
