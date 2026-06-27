<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Outbox;

interface OutboxMessageRepositoryInterface
{
    public function add(OutboxMessage $outboxMessage): void;

    /**
     * @return OutboxMessage[]
     */
    public function claimForProcessing(int $limit): array;

    public function markAsSent(string $id): bool;

    public function markAsFailed(string $id, string $lastError): bool;
}
