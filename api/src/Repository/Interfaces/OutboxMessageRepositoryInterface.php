<?php

declare(strict_types=1);

namespace Movioza\Repository\Interfaces;

use Doctrine\DBAL\Exception;
use Symfony\Component\Uid\Uuid;

interface OutboxMessageRepositoryInterface
{
    public function claimBatch(int $batchSize): array;

    /**
     * @throws Exception
     */
    public function markAsSent(Uuid $id): void;

    /**
     * @throws Exception
     */
    public function markAsFailed(Uuid $id, string $lastError): void;
}
