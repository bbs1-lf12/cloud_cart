<?php

declare(strict_types=1);

namespace App\Domain\Options\Entity;

use App\Common\Entity\AbstractEntity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: 'App\Domain\Options\Repository\OptionsRepository')]
class Options extends AbstractEntity
{
    #[Column(type: 'string')]
    private string $appName;
    #[Column(type: 'string', nullable: true)]
    private ?string $appLogo;

    public function getAppName(): string
    {
        return $this->appName;
    }

    public function setAppName(string $appName): void
    {
        $this->appName = $appName;
    }

    public function getAppLogo(): ?string
    {
        return $this->appLogo;
    }

    public function setAppLogo(?string $appLogo): void
    {
        $this->appLogo = $appLogo;
    }
}
