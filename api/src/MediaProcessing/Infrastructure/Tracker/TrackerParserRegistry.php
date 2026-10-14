<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Tracker;

use Movioza\MediaProcessing\Application\Tracker\TrackerParserInterface;
use Movioza\MediaProcessing\Application\Tracker\TrackerParserRegistryInterface;
use Movioza\MediaProcessing\Domain\Enum\TorrentTracker;

class TrackerParserRegistry implements TrackerParserRegistryInterface
{
    public function get(TorrentTracker $tracker): TrackerParserInterface
    {
        // TODO: Implement get() method.
    }
}
