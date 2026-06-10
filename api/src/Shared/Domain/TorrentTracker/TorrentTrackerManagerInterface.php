<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\TorrentTracker;

use Movioza\Shared\Domain\TorrentTracker\Exception\TorrentTrackerException;
use Movioza\Shared\Domain\ValueObject\MagnetLink;
use Movioza\Shared\Domain\ValueObject\TargetFilePath;
use Movioza\Shared\Domain\ValueObject\TorrentFile;
use Movioza\Shared\Domain\ValueObject\Url;

interface TorrentTrackerManagerInterface
{
    /**
     * @throws TorrentTrackerException
     */
    public function downloadTorrentFile(Url $url, TargetFilePath $targetFilePath): ?TorrentFile;

    /**
     * @throws TorrentTrackerException
     */
    public function getMagnetLink(Url $url): ?MagnetLink;
}
