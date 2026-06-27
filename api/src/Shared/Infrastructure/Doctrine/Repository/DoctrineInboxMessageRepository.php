<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Movioza\Shared\Domain\Inbox\InboxMessage;
use Movioza\Shared\Domain\Inbox\InboxMessageRepositoryInterface;
use Movioza\Shared\Domain\Inbox\InboxMessageStatus;

class DoctrineInboxMessageRepository extends AbstractDoctrineRepository implements InboxMessageRepositoryInterface
{
    protected string $entityClass = InboxMessage::class;

    public function add(InboxMessage $inboxMessage): void
    {
        $this->getEntityManager()->persist($inboxMessage);
        $this->getEntityManager()->flush();
    }

    /**
     * @throws Exception
     */
    public function markAsProcessing(string $idempotentKey): bool
    {
        return $this->getConnection()->update(
            'inbox_messages',
            [
                'status' => InboxMessageStatus::PROCESSING->value
            ],
            [
                'idempotent_key' => $idempotentKey,
                'status' => InboxMessageStatus::FAILED->value,
            ]
        ) > 0;
    }

    /**
     * @throws Exception
     */
    public function markAsFailed(string $idempotentKey): bool
    {
        return $this->getConnection()->update(
            'inbox_messages',
            [
                'status' => InboxMessageStatus::FAILED->value
            ],
            [
                'idempotent_key' => $idempotentKey,
                'status' => InboxMessageStatus::PROCESSING->value,
            ]
        ) > 0;
    }

    /**
     * @throws Exception
     */
    public function markAsProcessed(string $idempotentKey): bool
    {
        return $this->getConnection()->update(
            'inbox_messages',
            [
                'status' => InboxMessageStatus::PROCESSED->value
            ],
            [
                'idempotent_key' => $idempotentKey,
                'status' => InboxMessageStatus::PROCESSING->value,
            ]
        ) > 0;
    }
}
