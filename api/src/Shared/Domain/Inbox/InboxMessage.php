<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Inbox;

use Movioza\Shared\Domain\Entity\TimestampableTrait;
use Movioza\Shared\Domain\Entity\UuidIdentifierTrait;

class InboxMessage
{
    use UuidIdentifierTrait;
    use TimestampableTrait;

    private string $idempotentKey;

    private InboxMessageStatus $status;

    public function __construct(string $id, string $idempotentKey)
    {
        $this->id = $id;
        $this->idempotentKey = $idempotentKey;
        $this->status = InboxMessageStatus::PROCESSING;
    }

    public function getIdempotentKey(): string
    {
        return $this->idempotentKey;
    }

    public function getStatus(): InboxMessageStatus
    {
        return $this->status;
    }
}
