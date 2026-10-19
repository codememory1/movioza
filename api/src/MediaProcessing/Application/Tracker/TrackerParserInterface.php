<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Tracker;

use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;

interface TrackerParserInterface
{
    public function parse(PageUrl $pageUrl): ParsedDownloadSource;
}
