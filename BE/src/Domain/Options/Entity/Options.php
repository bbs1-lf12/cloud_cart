<?php

declare(strict_types=1);

namespace App\Domain\Options\Entity;

use Doctrine\ORM\Mapping\Entity;

#[Entity(repositoryClass: 'App\Domain\Options\Repository\OptionsRepository')]
class Options
{
    private string $appName;
    private string $appLogo;

    public function getAppName(): string
    {
        return $this->appName;
    }

    public function setAppName(string $appName): void
    {
        $this->appName = $appName;
    }

    public function getAppLogo(): string
    {
        return $this->appLogo;
    }

    public function setAppLogo(string $appLogo): void
    {
        $this->appLogo = $appLogo;
    }
}
