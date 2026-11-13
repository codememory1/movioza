<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Messenger;

use InvalidArgumentException;
use OutOfBoundsException;

final class DebeziumMessageRegistry
{
    /**
     * @var array<string, array<string, string>>
     */
    private array $messageClasses = [];

    public function register(string $schema, string $table, string $messageClass): void
    {
        if (!class_exists($messageClass)) {
            throw new InvalidArgumentException(sprintf(
                'The message class registered for table "%s" does not exist.',
                "$schema.$table"
            ));
        }

        $this->messageClasses[$schema][$table] = $messageClass;
    }

    public function resolve(string $schema, string $table): ?string
    {
        return $this->messageClasses[$schema][$table]
            ?? throw new OutOfBoundsException(sprintf(
                'No Debezium message class is registered for table "%s".',
                "$schema.$table"
            ));
    }
}
