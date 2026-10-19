<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Bus\Query;

interface QueryBusInterface
{
    /**
     * @template TResult
     *
     * @param QueryInterface<TResult> $query
     *
     * @return TResult
     */
    public function ask(QueryInterface $query): mixed;
}
