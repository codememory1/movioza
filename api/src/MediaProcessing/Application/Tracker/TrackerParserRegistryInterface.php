<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Tracker;

use LogicException;
use Movioza\MediaProcessing\Domain\Enum\TorrentTracker;

interface TrackerParserRegistryInterface
{
    /**
     * Returns the parser registered for the tracker.
     *
     * @throws LogicException When the tracker has no registered parser.
     */
    public function get(TorrentTracker $tracker): TrackerParserInterface;
}
