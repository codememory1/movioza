<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\NativeQuery;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

abstract class AbstractDoctrineRepository extends ServiceEntityRepository
{
    protected string $entityClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->entityClass);
    }

    /**
     * @throws Exception
     */
    protected function executeStatement(string $sql, array $params = [], array $types = []): int|string
    {
        return $this->getConnection()->executeStatement($sql, $params, $types);
    }

    protected function createNativeQuery(string $sql, ResultSetMapping $rsm): NativeQuery
    {
        return $this->getEntityManager()->createNativeQuery($sql, $rsm);
    }

    protected function getConnection(): Connection
    {
        return $this->getEntityManager()->getConnection();
    }
}
