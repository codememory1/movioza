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

    public function transactional(callable $operation): mixed
    {
        return $this->em->wrapInTransaction(static fn () => $operation());
    }
}
