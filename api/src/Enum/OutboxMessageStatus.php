<?php

declare(strict_types=1);

namespace Movioza\Enum;

enum OutboxMessageStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case SENT = 'sent';
}
