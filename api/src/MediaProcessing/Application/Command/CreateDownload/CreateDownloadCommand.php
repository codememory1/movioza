<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\CreateDownload;

use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

/**
 * @implements CommandInterface<void>
 */
final readonly class CreateDownloadCommand implements CommandInterface
{
    public function __construct(
        public DownloadID $downloadId,
        public DownloadSourceID $downloadSourceId
    ) {
    }
}
