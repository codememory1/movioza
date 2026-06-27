<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Inbox;

interface InboxMessageRepositoryInterface
{
    public function add(InboxMessage $inboxMessage): void;

    public function markAsProcessing(string $idempotentKey): bool;

    public function markAsFailed(string $idempotentKey): bool;

    public function markAsProcessed(string $idempotentKey): bool;
}
