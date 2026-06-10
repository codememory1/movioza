<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\TorrentTracker\Exception;

use Movioza\Shared\Domain\ValueObject\Url;

class NoTorrentTrackerProviderFoundException extends TorrentTrackerException
{
    public static function forUrl(Url $url): self
    {
        return new self(sprintf(
            'Torrent tracker provider for URL "%s" was not found.',
            $url
        ));
    }
}
