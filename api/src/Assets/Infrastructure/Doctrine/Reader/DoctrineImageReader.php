<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\Assets\Application\Reader\ImageReaderInterface;
use Movioza\Assets\Application\ReadModel\ImageView;

final readonly class DoctrineImageReader implements ImageReaderInterface
{
    public function __construct(
        private Connection $connection
    ) {
    }

    /**
     * @throws Exception
     */
    public function findByIds(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $views = [];
        $rows = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('images', 'i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::STRING)
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            $views[$row['id']] = new ImageView(
                $row['id'],
                '<url>',
                $row['blur_hash'],
                $row['width'],
                $row['height']
            );
        }

        return $views;
    }
}
