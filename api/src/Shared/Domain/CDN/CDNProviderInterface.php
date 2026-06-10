<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\CDN;

use Movioza\Shared\Domain\CDN\Enum\CDNFileType;

interface CDNProviderInterface
{
    public function supports(CdnFileType $type): bool;

    public function generate(CdnFileType $type, string $path, int $ttl): string;
}
