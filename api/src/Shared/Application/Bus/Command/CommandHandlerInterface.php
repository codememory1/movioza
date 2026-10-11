<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Command;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command): void;
}
