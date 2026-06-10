<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\CDN;

use Movioza\Shared\Domain\CDN\CDNProviderInterface;
use Movioza\Shared\Domain\CDN\CDNUrlGeneratorInterface;
use Movioza\Shared\Domain\CDN\Enum\CdnFileType;
use Movioza\Shared\Domain\CDN\Exception\NoCdnProviderFoundException;

readonly class CDNUrlGenerator implements CDNUrlGeneratorInterface
{
    /**
     * @param iterable<int, CDNProviderInterface> $providers
     */
    public function __construct(
        private iterable $providers
    ) {
    }

    public function generate(CdnFileType $type, string $path, int $ttl, int $stableId): string
    {
        $availableProviders = [];

        foreach ($this->providers as $provider) {
            if ($provider->supports($type)) {
                $availableProviders[] = $provider;
            }
        }

        if ($availableProviders === []) {
            throw NoCdnProviderFoundException::forType($type);
        }

        return $this->selectProvider($availableProviders, $stableId)->generate($type, $path, $ttl);
    }

    private function selectProvider(array $providers, int $stableId): CdnProviderInterface
    {
        return $providers[$stableId % count($providers)];
    }
}
