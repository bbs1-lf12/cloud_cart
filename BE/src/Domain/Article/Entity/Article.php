<?php

declare(strict_types=1);

namespace App\Domain\Article\Entity;

use App\Common\Entity\AbstractEntity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;

#[Entity]
class Article extends AbstractEntity
{
    #[Column(type: 'string')]
    #[Groups(['article:list'])]
    private string $title;
    #[Column(type: 'string')]
    #[Groups(['article:list'])]
    private string $description;
    #[Column(type: 'integer')]
    #[Groups(['article:list'])]
    private int $priceInCents;
    #[Column(type: 'integer')]
    #[Groups(['article:list'])]
    private int $stock;
    #[Column(type: 'boolean')]
    #[Groups(['article:list'])]
    private bool $isFeatured;
    #[Column(type: 'boolean')]
    private bool $isEnabled;
    #[Column(type: 'float')]
    #[Groups(['article:list'])]
    private float $score;
    #[ManyToOne(targetEntity: Category::class, inversedBy: 'articles')]
    #[Ignore]
    private Category $category;

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

    public function getScore(): float
    {
        return $this->score;
    }

    public function setScore(float $score): void
    {
        $this->score = $score;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }
}
