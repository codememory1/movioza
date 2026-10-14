<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Tracker\Storage;

interface TorrentFileStorageInterface
{
    /**
     * Saves torrent content and returns its local path.
     */
    public function save(string $content): string;
}
