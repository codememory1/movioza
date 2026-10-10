<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Exception;

abstract class DomainException extends \DomainException
{
    abstract public function getErrorCode(): string;
}
