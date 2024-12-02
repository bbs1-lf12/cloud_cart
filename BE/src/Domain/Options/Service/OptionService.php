<?php

declare(strict_types=1);

namespace App\Domain\Options\Service;

use App\Domain\Options\Entity\Options;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class OptionService
{
    public const APP_OPTIONS_CACHE = 'app_options';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TagAwareCacheInterface $cache,
    ) {
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getOptions(): Options
    {
        return $this->cache->get(
            self::APP_OPTIONS_CACHE,
            function (ItemInterface $item) {
                $item->tag(self::APP_OPTIONS_CACHE);
                return $this->entityManager
                    ->getRepository(Options::class)
                    ->getOptions()
                ;
            },
        );
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function saveOptions(Options $options): void
    {
        $this->cache
            ->invalidateTags([self::APP_OPTIONS_CACHE])
        ;
        if ($options->getId() === null) {
            $this->entityManager->persist($options);
        }
        $this->entityManager->flush();
    }
}
