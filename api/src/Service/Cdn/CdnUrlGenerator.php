<?php

declare(strict_types=1);

namespace Movioza\Service\Cdn;

use RuntimeException;

readonly class CdnUrlGenerator implements CdnUrlGeneratorInterface
{
    /**
     * @param iterable<int, CdnProviderInterface> $providers
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
            throw new RuntimeException("No CDN provider found for type \"$type->value\".");
        }

        return $this->selectProvider($availableProviders, $stableId)->generate($type, $path, $ttl);
    }

    private function selectProvider(array $providers, int $stableId): CdnProviderInterface
    {
        return $providers[$stableId % count($providers)];
    }
}
