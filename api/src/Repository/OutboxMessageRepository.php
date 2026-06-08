<?php

declare(strict_types=1);

namespace Movioza\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Entity\OutboxMessage;
use Movioza\Enum\OutboxMessageStatus;
use Movioza\Repository\Interfaces\OutboxMessageRepositoryInterface;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<OutboxMessage>
 */
class OutboxMessageRepository extends ServiceEntityRepository implements OutboxMessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutboxMessage::class);
    }

    public function claimBatch(int $batchSize): array
    {
        $em = $this->getEntityManager();

        $rsm = new ResultSetMappingBuilder($em);

        $rsm->addRootEntityFromClassMetadata(OutboxMessage::class, 'om');

        $query = $em->createNativeQuery(<<<SQL
        WITH filtered_outbox_messages AS (
            SELECT
                om.id
            FROM outbox_messages om
            WHERE om.status = :expected_status AND om.available_at <= NOW()
            ORDER BY om.id ASC
            LIMIT :limit
            FOR UPDATE SKIP LOCKED
        )
        UPDATE outbox_messages om
        SET
            status = :new_status,
            updated_at = NOW()
        FROM filtered_outbox_messages fom
        WHERE om.id = fom.id
        RETURNING om.*
        SQL, $rsm);

        $query->setParameter('expected_status', OutboxMessageStatus::PENDING->value);
        $query->setParameter('new_status', OutboxMessageStatus::PROCESSING->value);
        $query->setParameter('limit', $batchSize);

        return $query->getResult();
    }

    public function markAsSent(Uuid $id): void
    {
        $this->getEntityManager()->getConnection()->executeStatement(<<<SQL
        UPDATE outbox_messages
        SET
            status = :new_status,
            sent_at = NOW(),
            updated_at = NOW()
        WHERE id = :id AND status = :expected_status
        SQL, [
            'id' => $id->toString(),
            'new_status' => OutboxMessageStatus::SENT->value,
            'expected_status' => OutboxMessageStatus::PROCESSING->value,
        ]);
    }

    public function markAsFailed(Uuid $id, string $lastError): void
    {
        $this->getEntityManager()->getConnection()->executeStatement(<<<SQL
        UPDATE outbox_messages
        SET
            status = :new_status,
            attempts = attempts + 1,
            last_error = :last_error,
            available_at = NOW() + INTERVAL '10 second',
            updated_at = NOW()
        WHERE id = :id AND status = :expected_status
        SQL, [
            'id' => $id->toString(),
            'new_status' => OutboxMessageStatus::PENDING->value,
            'last_error' => $lastError,
            'expected_status' => OutboxMessageStatus::PROCESSING->value,
        ]);
    }
}
