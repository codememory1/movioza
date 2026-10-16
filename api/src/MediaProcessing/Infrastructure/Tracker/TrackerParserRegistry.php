<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Tracker;

use LogicException;
use Movioza\MediaProcessing\Application\Tracker\TrackerParserInterface;
use Movioza\MediaProcessing\Application\Tracker\TrackerParserRegistryInterface;
use Movioza\MediaProcessing\Domain\Enum\TorrentTracker;

final class TrackerParserRegistry implements TrackerParserRegistryInterface
{
    /**
     * @var array<string, TrackerParserInterface>
     */
    private array $parsers;

    /**
     * @param iterable<string, TrackerParserInterface> $parsers
     */
    public function __construct(iterable $parsers)
    {
        $this->parsers = is_array($parsers) ? $parsers : iterator_to_array($parsers);
    }

    public function get(TorrentTracker $tracker): TrackerParserInterface
    {
        return $this->parsers[$tracker->value]
            ?? throw new LogicException(sprintf('Parser for tracker "%s" is not registered.', $tracker->value));
    }
}
