<?php

declare(strict_types=1);

namespace App\Domain\Options\Service;

use App\Domain\Article\Service\ImageService;
use App\Domain\Options\Entity\Options;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class OptionService
{
    public const APP_OPTIONS_CACHE = 'app_options';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TagAwareCacheInterface $cache,
        private readonly ImageService $imageService,
    ) {
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getOptions(bool $bypass = false): Options
    {
        return $this->cache->get(
            self::APP_OPTIONS_CACHE,
            function (
                ItemInterface $item,
            ) {
                $item->tag(self::APP_OPTIONS_CACHE);
                return $this->entityManager
                    ->getRepository(Options::class)
                    ->getOptions()
                ;
            },
            $bypass
                ? INF
                : null,
        );
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function saveOptions(
        Options $savedOptions,
        FormInterface $form,
    ): void {
        $file = $form->get('appLogo')
            ->getData()
        ;

        if ($file !== null) {
            $fileName = $this->imageService
                ->upload($file)
            ;
            $savedOptions->setAppLogo($fileName);
        }

        $options = $this->getOptions(true);
        $options->setAppName($savedOptions->getAppName());
        $options->setAppLogo($savedOptions->getAppLogo());
        $options->setLowStockNotification($savedOptions->getLowStockNotification());
        $options->setCurrency($savedOptions->getCurrency());

        $this->entityManager->flush();

        $this->cache
            ->invalidateTags([self::APP_OPTIONS_CACHE])
        ;
    }
}
