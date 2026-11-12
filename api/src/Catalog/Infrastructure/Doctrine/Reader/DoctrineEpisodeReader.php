<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Reader;

use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Movioza\Catalog\Application\Reader\EpisodeReaderInterface;
use Movioza\Catalog\Application\Reader\ExternalImageReaderInterface;
use Movioza\Catalog\Application\ReadModel\EpisodeListItemView;
use Movioza\Catalog\Application\ReadModel\ImageView;
use Movioza\Catalog\Domain\Enum\EpisodeImageType;

final readonly class DoctrineEpisodeReader implements EpisodeReaderInterface
{
    public function __construct(
        private Connection $connection,
        private ExternalImageReaderInterface $externalImageReader
    ) {
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function findReleasedBySeasonID(int $seasonId): array
    {
        $rows = $this->connection
            ->createQueryBuilder()
            ->select(
                'e.id',
                'e.number',
                'e.slug',
                'e.title',
                'e.description',
                'e.duration',
                'e.released_at',
                'e.uploaded_at',
                'e.updated_at'
            )
            ->from('episodes', 'e')
            ->where('e.season_id = :season_id')
            ->andWhere('e.released_at IS NOT NULL')
            ->setParameter('season_id', $seasonId, ParameterType::INTEGER)
            ->orderBy('e.number', 'ASC')
            ->executeQuery()
            ->fetchAllAssociative();

        if ($rows === []) {
            return [];
        }

        $episodeIDs = array_map(static fn (array $row): int => $row['id'], $rows);
        $imagesByType = $this->readImages($episodeIDs);

        return array_map(static fn (array $row): EpisodeListItemView => new EpisodeListItemView(
            $row['number'],
            $row['slug'],
            $row['title'],
            $row['description'],
            $row['duration'],
            $imagesByType[EpisodeImageType::PREVIEW->value][0] ?? null,
            new DateTimeImmutable($row['released_at']),
            new DateTimeImmutable($row['uploaded_at']),
            $row['updated_at'] === null ? null : new DateTimeImmutable($row['updated_at']),
        ), $rows);
    }

    /**
     * @param list<int> $episodeIDs
     *
     * @return array<string, list<ImageView>>
     * @throws Exception
     */
    private function readImages(array $episodeIDs): array
    {
        $episodeImages = $this->connection
            ->createQueryBuilder()
            ->select('ei.episode_id', 'ei.image_id', 'ei.type')
            ->from('episode_images', 'ei')
            ->where('ei.episode_id IN (:episode_ids)')
            ->setParameter('episode_ids', $episodeIDs, ArrayParameterType::INTEGER)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($episodeImages === []) {
            return [];
        }

        $imageIDs = array_map(static fn (array $row): string => $row['image_id'], $episodeImages);
        $images = $this->externalImageReader->findByIDs($imageIDs);

        if ($images === []) {
            return [];
        }

        $imagesByType = [];

        foreach ($episodeImages as $episodeImage) {
            if (!isset($imagesByType[$episodeImage['type']])) {
                $imagesByType[$episodeImage['type']] = [];
            }

            if (isset($images[$episodeImage['image_id']])) {
                $imagesByType[$episodeImage['type']][] = $images[$episodeImage['image_id']];
            }
        }

        return $imagesByType;
    }
}
