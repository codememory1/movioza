<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Exception;

use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\Shared\Application\Exception\ApplicationException;

final class DownloadNotFoundException extends ApplicationException
{
    public function __construct(DownloadID $downloadId)
    {
        parent::__construct(sprintf(
            'Download "%s" was not found.',
            $downloadId->getValue()
        ));
    }
}
