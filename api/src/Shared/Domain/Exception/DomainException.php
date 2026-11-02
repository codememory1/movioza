<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Exception;

abstract class DomainException extends \DomainException
{
    /**
     * Returns the stable machine-readable domain error code.
     */
    abstract public function getErrorCode(): string;
}
