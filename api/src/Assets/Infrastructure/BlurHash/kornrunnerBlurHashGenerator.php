<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\BlurHash;

use kornrunner\Blurhash\Blurhash;
use Movioza\Assets\Application\BlurHash\BlurHashGeneratorInterface;
use RuntimeException;

final class kornrunnerBlurHashGenerator implements BlurHashGeneratorInterface
{
    public function generateFromFile(string $path): string
    {
        $contents = @file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException(sprintf('Could not read image file "%s".', $path));
        }

        return $this->generateFromContent($contents);
    }

    public function generateFromContent(string $content): string
    {
        $source = @imagecreatefromstring($content);

        if ($source === false) {
            throw new RuntimeException('Could not recognize the image.');
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $scale = min(1.0, 64 / max($sourceWidth, $sourceHeight));
        $width = max(1, (int) round($sourceWidth * $scale));
        $height = max(1, (int) round($sourceHeight * $scale));
        $thumbnail = imagecreatetruecolor($width, $height);

        if ($thumbnail === false) {
            throw new RuntimeException('Could not create image thumbnail.');
        }

        // Composite transparent pixels onto the #181a20 background.
        imagealphablending($thumbnail, true);

        $background = imagecolorallocate($thumbnail, 24, 26, 32);

        imagefill($thumbnail, 0, 0, $background);

        imagecopyresampled(
            $thumbnail,
            $source,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $sourceWidth,
            $sourceHeight,
        );

        unset($source, $contents);

        $pixels = [];

        for ($y = 0; $y < $height; ++$y) {
            $row = [];

            for ($x = 0; $x < $width; ++$x) {
                $color = imagecolorsforindex(
                    $thumbnail,
                    imagecolorat($thumbnail, $x, $y),
                );

                $row[] = [
                    $color['red'],
                    $color['green'],
                    $color['blue'],
                ];
            }

            $pixels[] = $row;
        }

        return Blurhash::encode($pixels, 4, 3);
    }
}
