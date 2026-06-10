<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Downloader\ValueObject;

use InvalidArgumentException;

class TorrentFile
{
    public string $value;

    public function __construct(string $value)
    {
        if (!file_exists($value)) {
            throw new InvalidArgumentException(sprintf('Torrent file "%s" does not exist', $value));
        }

        if (pathinfo($value, PATHINFO_EXTENSION) !== 'torrent') {
            throw new InvalidArgumentException(sprintf(
                'File "%s" must have the ".torrent" extension.',
                $value
            ));
        }

        $this->value = $value;
    }
}
