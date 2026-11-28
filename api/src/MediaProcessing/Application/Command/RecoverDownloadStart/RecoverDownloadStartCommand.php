<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\RecoverDownloadStart;

use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

final readonly class RecoverDownloadStartCommand implements CommandInterface
{
    public function __construct(
        public DownloadID $downloadId
    ) {
    }
}
