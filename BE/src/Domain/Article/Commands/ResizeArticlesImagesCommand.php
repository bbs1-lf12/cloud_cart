<?php

declare(strict_types=1);

namespace App\Domain\Article\Commands;

use App\Domain\Article\Service\ImageService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ResizeArticlesImagesCommand extends Command
{
    protected static $defaultName = 'app:article-resize-images';

    public function __construct(
        private readonly ImageService $imageService,
    ) {
        parent::__construct(self::$defaultName);
    }

    protected function configure(): void
    {
        $this->setDescription('Resize all articles images');
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        // get all images from public/images
        $images = scandir($_ENV['IMAGES_DIRECTORY']);

        foreach ($images as $image) {
            if (
                $image === '.'
                || $image === '..'
            ) {
                continue;
            }

            $imageFile = new UploadedFile(
                '/var/www/public/images/' . $image,
                $image,
            );

            $this->imageService
                ->resizeImage(
                    $imageFile,
                    $image,
                )
            ;
        }

        return Command::SUCCESS;
    }
}
