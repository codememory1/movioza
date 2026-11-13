<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Enum;

enum DebeziumOperation: string
{
    case UPDATE = 'u';
    case CREATE = 'c';
    case DELETE = 'd';

    // Emitted when an existing row is read during a snapshot.
    case READ = 'r';
}
