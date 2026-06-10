<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\TorrentTracker;

use Movioza\Shared\Domain\TorrentTracker\Exception\NoTorrentTrackerProviderFoundException;
use Movioza\Shared\Domain\TorrentTracker\TorrentTrackerManagerInterface;
use Movioza\Shared\Domain\TorrentTracker\TorrentTrackerProviderInterface;
use Movioza\Shared\Domain\ValueObject\MagnetLink;
use Movioza\Shared\Domain\ValueObject\TargetFilePath;
use Movioza\Shared\Domain\ValueObject\TorrentFile;
use Movioza\Shared\Domain\ValueObject\Url;

readonly class TorrentTrackerManager implements TorrentTrackerManagerInterface
{
    /**
     * @param iterable<TorrentTrackerProviderInterface> $providers
     */
    public function __construct(
        private iterable $providers
    ) {
    }

    public function downloadTorrentFile(Url $url, TargetFilePath $targetFilePath): ?TorrentFile
    {
        return $this->getProvider($url)->downloadTorrentFile($url, $targetFilePath);
    }

    public function getMagnetLink(Url $url): ?MagnetLink
    {
        return $this->getProvider($url)->getMagnetLink($url);
    }

    private function getProvider(Url $url): TorrentTrackerProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($url)) {
                return $provider;
            }
        }

        throw NoTorrentTrackerProviderFoundException::forUrl($url);
    }
}
