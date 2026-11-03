<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\ResolveMediaSource;

use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

final readonly class ResolveMediaSourceCommand implements CommandInterface
{
    public function __construct(
        public MediaSourceID $id
    ) {
    }
}
