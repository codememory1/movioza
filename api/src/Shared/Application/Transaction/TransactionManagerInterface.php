<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Transaction;

interface TransactionManagerInterface
{
    /**
     * Runs an operation within the persistence transaction boundary.
     * Returns the callback result and propagates failures after rollback.
     * External side effects are outside the database transaction.
     *
     * @template TResult
     * @param callable(): TResult $operation
     * @return TResult
     */
    public function transactional(callable $operation): mixed;
}
