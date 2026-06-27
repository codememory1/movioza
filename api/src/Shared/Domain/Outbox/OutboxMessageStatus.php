<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Outbox;

enum OutboxMessageStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SENT = 'sent';
    case FAILED = 'failed';
}
