<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Exception;

use Movioza\Shared\Application\Exception\ApplicationException;

final class SeasonNotFoundException extends ApplicationException
{
    public function __construct(int $seasonNumber)
    {
        parent::__construct(sprintf('Season with number %d was not found.', $seasonNumber));
    }
}
