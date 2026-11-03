<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Tracker;

use Movioza\MediaProcessing\Application\Exception\TrackerParsingFailedException;
use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;

interface TrackerParserRegistryInterface
{
    /**
     * Returns the first registered parser that supports the given page URL.
     *
     * @throws TrackerParsingFailedException When no registered parser supports the given page URL.
     */
    public function get(PageUrl $pageUrl): TrackerParserInterface;
}
