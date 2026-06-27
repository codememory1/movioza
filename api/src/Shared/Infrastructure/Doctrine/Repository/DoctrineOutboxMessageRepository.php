<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Movioza\Shared\Domain\Outbox\OutboxMessage;
use Movioza\Shared\Domain\Outbox\OutboxMessageRepositoryInterface;
use Movioza\Shared\Domain\Outbox\OutboxMessageStatus;

class DoctrineOutboxMessageRepository extends AbstractDoctrineRepository implements OutboxMessageRepositoryInterface
{
    protected string $entityClass = OutboxMessage::class;

    public function add(OutboxMessage $outboxMessage): void
    {
        $this->getEntityManager()->persist($outboxMessage);
        $this->getEntityManager()->flush();
    }

    public function claimForProcessing(int $limit): array
    {
        $rsm = $this->createResultSetMappingBuilder('om');

        $sql = <<<SQL
        WITH claimed AS (
            SELECT
                *
            FROM outbox_messages
            WHERE status = :pendingStatus
                AND available_at <= NOW()
            ORDER BY available_at, id
            LIMIT :limit
            FOR UPDATE SKIP LOCKED
        )
        UPDATE outbox_messages om
        SET status = :processingStatus
        FROM claimed c
        WHERE om.id = c.id
        RETURNING {$rsm->generateSelectClause()}
        SQL;

        $query = $this->createNativeQuery($sql, $rsm);

        $query->setParameter('pendingStatus', OutboxMessageStatus::PENDING->value);
        $query->setParameter('processingStatus', OutboxMessageStatus::PROCESSING->value);
        $query->setParameter('limit', $limit);

        return $query->getResult();
    }

    /**
     * @throws Exception
     */
    public function markAsSent(string $id): bool
    {
        $sql = <<<SQL
        UPDATE outbox_messages
        SET status = :sentStatus,
            sent_at = NOW()
        WHERE id = :id AND status = :processingStatus
        SQL;

        return $this->getConnection()->executeStatement($sql, [
            'sentStatus' => OutboxMessageStatus::SENT->value,
            'id' => $id,
            'processingStatus' => OutboxMessageStatus::PROCESSING->value,
        ]) > 0;
    }

    /**
     * @throws Exception
     */
    public function markAsFailed(string $id, string $lastError): bool
    {
        $sql = <<<SQL
        UPDATE outbox_messages
        SET status = :failedStatus,
            last_error = :lastError,
            attempts = attempts + 1,
            available_at = NOW() + (LEAST(300, POWER(2, attempts + 1)) * INTERVAL '1 second')
            failed_at = NOW()
        WHERE id = :id AND status = :processingStatus
        SQL;

        return $this->getConnection()->executeStatement($sql, [
            'failedStatus' => OutboxMessageStatus::FAILED->value,
            'lastError' => $lastError,
            'id' => $id,
            'processingStatus' => OutboxMessageStatus::PROCESSING->value,
        ]) > 0;
    }
}
