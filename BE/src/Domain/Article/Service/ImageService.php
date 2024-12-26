<?php

declare(strict_types=1);

namespace App\Domain\Article\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageService
{
    private string $targetDirectory;
    public function __construct(
        private readonly SluggerInterface $slugger,
    ) {
        $this->targetDirectory = $_ENV['IMAGES_DIRECTORY'];
    }

    public function upload(UploadedFile $file): string
    {
        $originalFilename = pathinfo(
            $file->getClientOriginalPath(),
            PATHINFO_FILENAME,
        );
        $safeFilename = $this->slugger
            ->slug($originalFilename)
        ;
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move(
                $this->getTargetDirectory(),
                $fileName,
            );
        } catch (FileException $e) {
            throw new FileException(
                $e->getMessage(),
            );
        }

        return $fileName;
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
