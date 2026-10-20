<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\CreateMediaSource;

use Movioza\MediaProcessing\Domain\ValueObject\MediaID;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

final class CreateMediaSourceCommand implements CommandInterface
{
    public function __construct(
        public MediaSourceID $ID,
        public MediaID $mediaID,
        public PageUrl $pageUrl
    ) {
    }
}
