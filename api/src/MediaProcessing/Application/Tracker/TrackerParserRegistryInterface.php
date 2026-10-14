<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Tracker;

use Movioza\MediaProcessing\Domain\Enum\TorrentTracker;

interface TrackerParserRegistryInterface
{
    public function get(TorrentTracker $tracker): TrackerParserInterface;
}
