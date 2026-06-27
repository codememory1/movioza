<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Outbox;

use DateTimeImmutable;
use Movioza\Shared\Domain\Entity\TimestampableTrait;
use Movioza\Shared\Domain\Entity\UuidIdentifierTrait;

class OutboxMessage
{
    use UuidIdentifierTrait;
    use TimestampableTrait;

    private string $messageClass;

    private array $payload;

    private OutboxMessageStatus $status;

    private int $attempts = 0;

    private ?string $lastError = null;

    private DateTimeImmutable $availableAt;

    private ?DateTimeImmutable $sentAt = null;

    private ?DateTimeImmutable $failedAt = null;

    public function __construct(string $id, string $messageClass, array $payload = [])
    {
        $this->id = $id;
        $this->messageClass = $messageClass;
        $this->payload = $payload;
        $this->status = OutboxMessageStatus::PENDING;
        $this->availableAt = new DateTimeImmutable();
    }

    public function getMessageClass(): string
    {
        return $this->messageClass;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getStatus(): OutboxMessageStatus
    {
        return $this->status;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function getAvailableAt(): DateTimeImmutable
    {
        return $this->availableAt;
    }

    public function getSentAt(): ?DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function getFailedAt(): ?DateTimeImmutable
    {
        return $this->failedAt;
    }
}
