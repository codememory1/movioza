<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\ReadModel;

final readonly class ImageView
{
    public function __construct(
        public string $id,
        public string $url,
        public string $blurHash,
        public int $width,
        public int $height
    ) {
    }
}
