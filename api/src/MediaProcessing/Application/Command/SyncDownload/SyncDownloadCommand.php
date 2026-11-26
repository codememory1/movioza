<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\SyncDownload;

use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\Shared\Application\Bus\Command\CommandInterface;

/**
 * @implements CommandInterface<void>
 */
final readonly class SyncDownloadCommand implements CommandInterface
{
    public function __construct(
        public DownloadID $downloadId
    ) {
    }
}
