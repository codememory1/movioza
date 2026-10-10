<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Query;

interface QueryHandlerInterface
{
    public function handle(QueryInterface $query): mixed;
}
