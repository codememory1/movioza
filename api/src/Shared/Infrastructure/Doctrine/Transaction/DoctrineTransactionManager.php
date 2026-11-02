<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Transaction;

use Doctrine\ORM\EntityManagerInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;

readonly class DoctrineTransactionManager implements TransactionManagerInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * Runs the callback in an ORM transaction and flushes managed changes before commit.
     * The callback result is returned; failures roll back the transaction and propagate.
     *
     * @template TResult
     * @param callable(): TResult $operation
     * @return TResult
     */
    public function transactional(callable $operation): mixed
    {
        return $this->em->wrapInTransaction(static fn () => $operation());
    }
}
