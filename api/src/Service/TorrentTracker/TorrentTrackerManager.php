<?php

declare(strict_types=1);

namespace Movioza\Service\TorrentTracker;

use RuntimeException;

readonly class TorrentTrackerManager implements TorrentTrackerManagerInterface
{
    /**
     * @param iterable<ProviderInterface> $providers
     */
    public function __construct(
        private iterable $providers
    ) {
    }

    public function getMagnetLink(string $url): ?string
    {
        return $this->getProvider($url)->getMagnetLink($url);
    }

    public function downloadTorrentFile(string $url, string $targetPath): bool
    {
        return $this->getProvider($url)->downloadTorrentFile($url, $targetPath);
    }

    private function getProvider(string $url): ProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($url)) {
                return $provider;
            }
        }

        throw new RuntimeException(sprintf('Torrent tracker provider for URL "%s" was not found.', $url));
    }
}
