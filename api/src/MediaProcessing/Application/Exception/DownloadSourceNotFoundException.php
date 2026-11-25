<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Exception;

use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;
use Movioza\Shared\Application\Exception\ApplicationException;

final class DownloadSourceNotFoundException extends ApplicationException
{
    public function __construct(DownloadSourceID $downloadSourceId)
    {
        parent::__construct(sprintf(
            'Download source "%s" was not found.',
            $downloadSourceId->getValue()
        ));
    }
}
