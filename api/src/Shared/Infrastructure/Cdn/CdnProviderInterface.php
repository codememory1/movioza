<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Cdn;

use Movioza\Shared\Application\Enum\CdnFileType;

interface CdnProviderInterface
{
    public function supports(CdnFileType $type): bool;

    /**
     * Generates a URL for a supported file type.
     *
     * @param string $path Delivery path relative to this provider.
     * @param int $ttl URL lifetime in seconds.
     */
    public function generate(CdnFileType $type, string $path, int $ttl): string;
}
