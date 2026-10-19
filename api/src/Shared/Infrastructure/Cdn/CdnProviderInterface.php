<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Cdn;

use Movioza\Shared\Application\Enum\CdnFileType;

interface CdnProviderInterface
{
    public function supports(CdnFileType $type): bool;

    public function generate(CdnFileType $type, string $path, int $ttl): string;
}
