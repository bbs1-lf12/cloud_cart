<?php

declare(strict_types=1);

namespace App\Domain\Article\Entity;

use App\Common\Entity\AbstractEntity;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[Entity(repositoryClass: 'App\Domain\Article\Repository\ArticleRepository')]
class Article extends AbstractEntity
{
    #[Column(type: 'string')]
    #[Groups(['article:list'])]
    private string $title;
    #[Column(type: 'string', length: 1024)]
    #[Groups(['article:list'])]
    private string $description;
    #[Column(type: 'integer')]
    #[Groups(['article:list'])]
    private int $priceInCents;
    #[Column(type: 'integer')]
    #[Groups(['article:list'])]
    private int $stock;
    #[Column(type: 'string', nullable: true)]
    #[Groups(['article:list'])]
    private ?string $image;
    #[Column(type: 'boolean')]
    #[Groups(['article:list'])]
    private bool $isFeatured;
    #[Column(type: 'boolean')]
    private bool $isEnabled;
    #[ManyToOne(targetEntity: Category::class, inversedBy: 'articles')]
    #[Ignore]
    private Category $category;
    #[OneToMany(targetEntity: Comment::class, mappedBy: 'article', cascade: ['remove'])]
    private Collection $comments;
    #[OneToMany(targetEntity: Score::class, mappedBy: 'article')]
    private Collection $scores;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPriceInCents(): int
    {
        return $this->priceInCents;
    }

    public function setPriceInCents(int $priceInCents): void
    {
        $this->priceInCents = $priceInCents;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

    public function isFeatured(): bool
    {
        return $this->isFeatured;
    }

    public function setIsFeatured(bool $isFeatured): void
    {
        $this->isFeatured = $isFeatured;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    public function addComment(Comment $comment): void
    {
        $this->comments->add($comment);
    }

    public function removeComment(Comment $comment): void
    {
        $this->comments->removeElement($comment);
    }

    public function hasComment(Comment $comment): bool
    {
        /** @var Comment $c */
        foreach ($this->comments as $c) {
            if ($c->getId() === $comment->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function setScores(Collection $scores): void
    {
        $this->scores = $scores;
    }

    public function hasScore(Score $score): bool
    {
        foreach ($this->scores as $s) {
            if ($s->getId() === $score->getId()) {
                return true;
            }
        }
        return false;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
}
