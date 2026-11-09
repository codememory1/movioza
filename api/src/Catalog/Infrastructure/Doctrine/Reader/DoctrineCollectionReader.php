<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\Catalog\Application\Reader\CollectionReaderInterface;
use Movioza\Catalog\Application\ReadModel\MainCollectionView;
use Movioza\Catalog\Domain\Enum\CollectionStatus;

final readonly class DoctrineCollectionReader implements CollectionReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function findMain(int $limit): array
    {
        $rows = $this->connection
            ->createQueryBuilder()
            ->select('c.id', 'c.slug', 'c.title')
            ->from('collections', 'c')
            ->where('c.status = :status')
            ->setParameter('status', CollectionStatus::PUBLISHED->value)
            ->setMaxResults($limit)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($rows === []) {
            return [];
        }

        return array_map(static fn (array $row) => new MainCollectionView(
            $row['id'],
            $row['slug'],
            $row['title']
        ), $rows);
    }

}
