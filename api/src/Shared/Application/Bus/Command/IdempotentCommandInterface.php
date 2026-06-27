<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Command;

interface IdempotentCommandInterface extends CommandInterface
{
    public function getIdempotentKey(): string;
}
