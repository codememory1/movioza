<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Inbox;

enum InboxMessageStatus: string
{
    case PROCESSING = 'processing';
    case PROCESSED = 'processed';
    case FAILED = 'failed';
}
