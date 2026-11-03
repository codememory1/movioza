<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Tracker;

use Movioza\MediaProcessing\Application\Exception\TrackerParsingFailedException;
use Movioza\MediaProcessing\Application\Tracker\TrackerParserInterface;
use Movioza\MediaProcessing\Application\Tracker\TrackerParserRegistryInterface;
use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;

final class TrackerParserRegistry implements TrackerParserRegistryInterface
{
    /**
     * @var array<string, TrackerParserInterface>
     */
    private array $parsers;

    /**
     * Indexes parsers by TorrentTracker values supplied by the tagged iterator.
     *
     * @param iterable<string, TrackerParserInterface> $parsers
     */
    public function __construct(iterable $parsers)
    {
        $this->parsers = is_array($parsers) ? $parsers : iterator_to_array($parsers);
    }

    public function get(PageUrl $pageUrl): TrackerParserInterface
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports($pageUrl)) {
                return $parser;
            }
        }

        throw new TrackerParsingFailedException('Tracker page contains no supported download source.');
    }
}
