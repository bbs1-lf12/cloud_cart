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
            $this->resizeImage(
                $file,
                $file->getFilename(),
            );

            $file->move(
                $this->targetDirectory,
                $fileName,
            );
        } catch (FileException $e) {
            throw new FileException(
                $e->getMessage(),
            );
        }

        return $fileName;
    }

    private function resizeImage(
        UploadedFile $file,
        string $fileName,
    ): void {
        /** @var ?\GdImage $image */
        $uploadedImg = $this->getImage(
            $file,
            $fileName,
        );
        if (!$uploadedImg) {
            return;
        }

        $uploadedWidth = imagesx($uploadedImg);
        $uploadedHeight = imagesy($uploadedImg);
        $greaterSide = max(
            $uploadedWidth,
            $uploadedHeight,
        );

        $image = imagecreatetruecolor(
            $greaterSide,
            $greaterSide,
        );
        $white = imagecolorallocate(
            $image,
            255,
            255,
            255,
        );
        imagefill(
            $image,
            0,
            0,
            $white,
        );

        $x = (int) (($greaterSide - $uploadedWidth) / 2);
        $y = (int) (($greaterSide - $uploadedHeight) / 2);

        imagecopy(
            $image,
            $uploadedImg,
            $x,
            $y,
            0,
            0,
            $uploadedWidth,
            $uploadedHeight,
        );

        $this->saveImage(
            $file,
            $fileName,
            $image,
        );
    }

    private function getImage(
        UploadedFile $file,
        string $fileName,
    ): ?\GdImage {
        $extension = $file->guessExtension();
        if (
            $extension === "jpg"
            || $extension === "jpeg"
        ) {
            return imagecreatefromjpeg($file->getPath() . '/' . $fileName);
        }
        if (
            $extension === "png"
        ) {
            return imagecreatefromjpeg($file->getPath() . '/' . $fileName);
        }
        return null;
    }

    private function saveImage(
        UploadedFile $file,
        string $fileName,
        \GdImage $image,
    ): void {
        $extension = $file->guessExtension();
        if (
            $extension === "jpg"
            || $extension === "jpeg"
        ) {
            imagejpeg(
                $image,
                $file->getPath() . '/' . $fileName,
            );
        }
        if (
            $extension === "png"
        ) {
            imagepng(
                $image,
                $file->getPath() . '/' . $fileName,
            );
        }
    }
}
