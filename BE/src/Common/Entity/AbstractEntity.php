<?php

declare(strict_types=1);

namespace App\Common\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\Serializer\Attribute\Groups;

#[MappedSuperclass]
class AbstractEntity
{
    #[Id]
    #[Column(type: 'integer')]
    #[GeneratedValue]
    #[Groups([
        'cart:list',
        'article:list',
        'comment:list',
        'score:list',
    ])]
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }
}
