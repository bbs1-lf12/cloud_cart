<?php

declare(strict_types=1);

namespace App\Domain\Article\Entity;

use App\Common\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: 'App\Domain\Article\Repository\CategoryRepository')]
class Category extends AbstractEntity
{
    #[Column(type: 'string')]
    private string $name;
    #[Column(type: 'string')]
    private string $description;
    #[Column(type: 'boolean')]
    private bool $isEnabled;
    #[OneToMany(targetEntity: Article::class, mappedBy: 'category')]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): void
    {
        $this->isEnabled = $isEnabled;
    }

    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function setArticles(Collection $articles): void
    {
        $this->articles = $articles;
    }
}
