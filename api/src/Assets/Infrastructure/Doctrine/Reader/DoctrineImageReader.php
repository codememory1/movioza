<?php

declare(strict_types = 1);

namespace Movioza\Assets\Infrastructure\Doctrine\Reader;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Movioza\Assets\Application\File\FileUrlGeneratorInterface;
use Movioza\Assets\Application\Reader\ImageReaderInterface;
use Movioza\Assets\Application\ReadModel\ImageView;
use Movioza\Shared\Application\Cdn\CdnUrlGeneratorInterface;
use Movioza\Shared\Application\Enum\CdnFileType;

final readonly class DoctrineImageReader implements ImageReaderInterface
{
    public function __construct(
        private Connection $connection,
        private FileUrlGeneratorInterface $imageUrlGenerator
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
            ->select(
                'i.id',
                'i.file_id',
                'i.blur_hash',
                'i.width',
                'i.height',
                'f.key AS file_key'
            )
            ->from('images', 'i')
            ->innerJoin('i', 'files', 'f', 'f.id = i.file_id')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $ids, ArrayParameterType::STRING)
            ->executeQuery()
            ->fetchAllAssociative();

        foreach ($rows as $row) {
            $views[$row['id']] = new ImageView(
                $row['id'],
                $this->imageUrlGenerator->generate($row['file_key'], $row['file_id']),
                $row['blur_hash'],
                $row['width'],
                $row['height']
            );
        }

        return $views;
    }
}
