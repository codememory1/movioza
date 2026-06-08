<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use DateMalformedStringException;
use DateTimeImmutable;
use Movioza\Enum\OutboxMessageStatus;
use Throwable;

interface OutboxMessageInterface extends UuidIdentifierInterface
{
    public string $messageClass {
        get;
    }

    public array $payload {
        get;
    }

    public OutboxMessageStatus $status {
        get;
    }

    public ?DateTimeImmutable $processingAt {
        get;
    }

    public ?DateTimeImmutable $sentAt {
        get;
    }

    public function markAsSent(): static;

    /**
     * @throws DateMalformedStringException
     */
    public function markAsFailed(Throwable $e): static;
}
