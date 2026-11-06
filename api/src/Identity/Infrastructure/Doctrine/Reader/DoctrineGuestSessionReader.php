<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Reader;

use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\Identity\Application\Reader\GuestSessionReaderInterface;
use Movioza\Identity\Application\ReadModel\GuestSessionView;

final readonly class DoctrineGuestSessionReader implements GuestSessionReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function findByID(string $id): ?GuestSessionView
    {
        $row = $this->connection
            ->createQueryBuilder()
            ->select('id', 'token', 'ip', 'user_agent', 'locale', 'timezone', 'expires_at')
            ->from('guest_sessions', 'gs')
            ->where('gs.id = :id')
            ->setParameter('id', $id)
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new GuestSessionView(
            $row['id'],
            $row['token'],
            $row['ip'],
            $row['user_agent'],
            $row['locale'],
            $row['timezone'],
            new DateTimeImmutable($row['expires_at'])
        );
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function findByToken(string $token): ?GuestSessionView
    {
        $row = $this->connection
            ->createQueryBuilder()
            ->select('id', 'token', 'ip', 'user_agent', 'locale', 'timezone', 'expires_at')
            ->from('guest_sessions', 'gs')
            ->where('gs.token = :token')
            ->setParameter('token', $token)
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            return null;
        }

        return new GuestSessionView(
            $row['id'],
            $row['token'],
            $row['ip'],
            $row['user_agent'],
            $row['locale'],
            $row['timezone'],
            new DateTimeImmutable($row['expires_at'])
        );
    }
}
