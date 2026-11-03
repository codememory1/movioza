<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Tracker;

use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;

interface TrackerParserInterface
{
    /**
     * Checks whether this parser supports the given tracker page URL.
     *
     * Must not perform network requests.
     */
    public function supports(PageUrl $pageUrl): bool;

    /**
     * Resolves a tracker page into a supported download source.
     * A torrent result refers to a file saved locally by the parser implementation.
     */
    public function parse(PageUrl $pageUrl): ParsedDownloadSource;
}
