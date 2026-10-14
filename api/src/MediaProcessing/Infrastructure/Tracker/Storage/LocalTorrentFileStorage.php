<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Tracker\Storage;

use Random\RandomException;
use RuntimeException;

final readonly class LocalTorrentFileStorage implements TorrentFileStorageInterface
{
    public function __construct(
        private string $directory
    ) {
    }

    /**
     * @throws RandomException
     */
    public function save(string $content): string
    {
        if (trim($content) === '') {
            throw new RuntimeException('Torrent content must not be empty.');
        }

        if (!is_dir($this->directory) && !mkdir($this->directory, 0775, recursive: true) && !is_dir($this->directory)) {
            throw new RuntimeException('Cannot create torrent directory.');
        }

        $path = $this->buildPath($this->generateFilename());
        $writtenBytes = file_put_contents($path, $content, LOCK_EX);

        if ($writtenBytes === false || $writtenBytes !== strlen($content)) {
            if (is_file($path)) {
                unlink($path);
            }

            throw new RuntimeException('Cannot save torrent file.');
        }

        return $path;
    }

    private function buildPath(string $filename): string
    {
        return sprintf('%s/%s', rtrim($this->directory, '/'), ltrim($filename, '/'));
    }

    /**
     * @throws RandomException
     */
    private function generateFilename(): string
    {
        return sprintf('%s.torrent', bin2hex(random_bytes(16)));
    }
}
