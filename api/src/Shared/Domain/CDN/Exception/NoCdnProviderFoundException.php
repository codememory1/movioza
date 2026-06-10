<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\CDN\Exception;

use DomainException;
use Movioza\Shared\Domain\CDN\Enum\CDNFileType;

class NoCdnProviderFoundException extends DomainException
{
    public static function forType(CDNFileType $type): self
    {
        return new self(sprintf('No CDN provider found for type "%s".', $type->value));
    }
}
