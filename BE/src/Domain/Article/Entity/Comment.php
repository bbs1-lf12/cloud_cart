<?php

declare(strict_types=1);

namespace App\Domain\Article\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\User\Entity\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Attribute\Groups;

#[Entity(repositoryClass: 'App\Domain\Article\Repository\CommentRepository')]
class Comment extends AbstractEntity
{
    #[Groups(['comment:list'])]
    #[Column(type: 'string')]
    private string $content;
    #[ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    private User $user;
    #[ManyToOne(targetEntity: Article::class, inversedBy: 'comments')]
    private Article $article;

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    public function getArticle(): Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): void
    {
        $this->article = $article;
    }
}
