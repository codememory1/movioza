<?php

declare(strict_types=1);

namespace Movioza\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Entity\Interfaces\OutboxMessageInterface;
use Movioza\Entity\Traits\CreatedAtTrait;
use Movioza\Entity\Traits\UpdatedAtTrait;
use Movioza\Entity\Traits\UuidIdentifierTrait;
use Movioza\Enum\OutboxMessageStatus;
use Movioza\Repository\OutboxMessageRepository;
use Throwable;

#[ORM\Entity(repositoryClass: OutboxMessageRepository::class)]
#[ORM\Table(name: 'outbox_messages')]
#[ORM\HasLifecycleCallbacks]
class OutboxMessage implements OutboxMessageInterface
{
    use UuidIdentifierTrait;
    use CreatedAtTrait;
    use UpdatedAtTrait;

    #[ORM\Column(length: 255)]
    public private(set) string $messageClass {
        get => $this->messageClass;
    }

    #[ORM\Column(type: Types::JSON)]
    public private(set) array $payload {
        get => $this->payload;
    }

    #[ORM\Column(length: 32, enumType: OutboxMessageStatus::class)]
    public private(set) OutboxMessageStatus $status {
        get => $this->status;
    }

    #[ORM\Column]
    public private(set) int $attempts = 0 {
        get => $this->attempts;
    }

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    public ?string $lastError = null {
        get => $this->lastError;
        set => $this->lastError = $value;
    }

    #[ORM\Column]
    public private(set) DateTimeImmutable $availableAt {
        get => $this->availableAt;
    }

    #[ORM\Column(nullable: true)]
    public private(set) ?DateTimeImmutable $processingAt = null {
        get => $this->processingAt;
    }

    #[ORM\Column(nullable: true)]
    public private(set) ?DateTimeImmutable $sentAt = null {
        get => $this->sentAt;
    }

    public function __construct(string $messageClass, array $payload = [])
    {
        $this->messageClass = $messageClass;
        $this->payload = $payload;
        $this->status = OutboxMessageStatus::PENDING;
        $this->availableAt = new DateTimeImmutable();
    }

    public function markAsSent(): static
    {
        $this->status = OutboxMessageStatus::SENT;
        $this->sentAt = new DateTimeImmutable();

        return $this;
    }

    public function markAsFailed(Throwable $e): static
    {
        ++$this->attempts;

        $this->status = OutboxMessageStatus::PENDING;
        $this->lastError = $e->getMessage();
        $this->availableAt = new DateTimeImmutable(sprintf('+%d seconds', 2 ** $this->attempts));

        return $this;
    }
}
