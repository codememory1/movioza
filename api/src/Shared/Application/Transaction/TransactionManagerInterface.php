<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Transaction;

interface TransactionManagerInterface
{
    /**
     * @template TResult
     *
     * @param callable(): TResult $operation
     *
     * @return TResult
     */
    public function transactional(callable $operation): mixed;
}
