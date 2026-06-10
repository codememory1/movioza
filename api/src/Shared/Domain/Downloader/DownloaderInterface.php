<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Downloader;

use Movioza\Shared\Domain\Downloader\Exception\DownloaderException;
use Movioza\Shared\Domain\Downloader\ValueObject\DownloadMetrics;
use Movioza\Shared\Domain\ValueObject\MagnetLink;
use Movioza\Shared\Domain\ValueObject\TorrentFile;

interface DownloaderInterface
{
    /**
     * @throws DownloaderException
     */
    public function downloadTorrent(TorrentFile $file): string;

    /**
     * @throws DownloaderException
     */
    public function downloadMagnet(MagnetLink $magnetLink): string;

    /**
     * @throws DownloaderException
     */
    public function getMetrics(string $gid): DownloadMetrics;

    /**
     * @throws DownloaderException
     *
     * @return string[]
     */
    public function getActiveGIDs(): array;

    /**
     * @throws DownloaderException
     */
    public function cancel(string $gid): void;
}
