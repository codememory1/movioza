<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Cdn;

use Movioza\Shared\Application\Enum\CdnFileType;

interface CdnUrlGeneratorInterface
{
    /**
     * Generates a delivery URL using a provider that supports the requested file type.
     *
     * @param string $path Delivery path relative to the provider domain.
     * @param int $ttl URL lifetime in seconds from generation time.
     * @param int $stableId Non-negative key used for deterministic provider selection.
     */
    public function generate(CdnFileType $type, string $path, int $ttl, int $stableId): string;
}
