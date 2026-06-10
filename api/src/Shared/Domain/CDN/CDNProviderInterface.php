<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\CDN;

use Movioza\Shared\Domain\CDN\Enum\CDNFileType;

interface CDNProviderInterface
{
    public function supports(CDNFileType $type): bool;

    public function generate(CDNFileType $type, string $path, int $ttl): string;
}
