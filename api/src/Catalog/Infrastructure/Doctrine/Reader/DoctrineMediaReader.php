<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Reader;

use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;
use Movioza\Catalog\Application\Reader\ExternalImageReaderInterface;
use Movioza\Catalog\Application\Reader\MediaReaderInterface;
use Movioza\Catalog\Application\ReadModel\ImageView;
use Movioza\Catalog\Application\ReadModel\MediaView;
use Movioza\Catalog\Domain\Enum\MediaImageType;
use Movioza\Catalog\Domain\Enum\MediaKind;
use Movioza\Catalog\Domain\Enum\MediaType;
use Movioza\Shared\Application\Reader\CountryReaderInterface;
use UnexpectedValueException;

final readonly class DoctrineMediaReader implements MediaReaderInterface
{
    public function __construct(
        private Connection $connection,
        private ExternalImageReaderInterface $externalImageReader,
        private CountryReaderInterface $countryReader
    ) {
    }

    /**
     * @throws DateMalformedStringException
     * @throws Exception
     */
    public function findById(int $id): ?MediaView
    {
        $row = $this->connection
            ->createQueryBuilder()
            ->select('*')
            ->from('media', 'm')
            ->where('m.id = :id')
            ->setParameter('id', $id, ParameterType::INTEGER)
            ->executeQuery()
            ->fetchAssociative();

        if ($row === false) {
            return null;
        }

        $images = $this->readRequiredImages($id);
        $countries = $this->readCountries($id);

        return new MediaView(
            (int) $row['id'],
            MediaType::from($row['type']),
            $row['kind'] === null ? null : MediaKind::from($row['kind']),
            $row['title'],
            $row['slug'],
            $row['description'],
            (int) $row['age_restrictions'],
            $countries,
            $images[MediaImageType::LOGO->value],
            $images[MediaImageType::POSTER->value],
            $images[MediaImageType::BACKDROP->value],
            $this->toDateTime($row['released_at']),
            $this->toDateTime($row['uploaded_at']),
            $this->toDateTime($row['updated_at']),
        );
    }

    /**
     * @throws Exception
     */
    public function exists(int $id): bool
    {
        return $this->connection
            ->createQueryBuilder()
            ->select('1')
            ->from('media', 'm')
            ->where('m.id = :id')
            ->setParameter('id', $id, ParameterType::INTEGER)
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne() !== false;
    }

    /**
     * @return array<string, ImageView> Images indexed by media image type.
     *
     * @throws Exception
     * @throws UnexpectedValueException
     */
    private function readRequiredImages(int $mediaId): array
    {
        $types = [
            MediaImageType::LOGO->value,
            MediaImageType::POSTER->value,
            MediaImageType::BACKDROP->value,
        ];

        $imageIdsByType = $this->connection
            ->createQueryBuilder()
            ->select('mi.type', 'mi.image_id')
            ->from('media_images', 'mi')
            ->where('mi.media_id = :media_id')
            ->andWhere('mi.type IN (:types)')
            ->setParameter('media_id', $mediaId, ParameterType::INTEGER)
            ->setParameter('types', $types, ArrayParameterType::STRING)
            ->executeQuery()
            ->fetchAllKeyValue();

        foreach ($types as $type) {
            if (!isset($imageIdsByType[$type])) {
                throw new UnexpectedValueException(sprintf('Media %s is not assigned.', $type));
            }
        }

        $imagesById = $this->externalImageReader->findByIds(
            array_values(array_unique($imageIdsByType))
        );

        $imagesByType = [];

        foreach ($types as $type) {
            $imageId = $imageIdsByType[$type];

            if (!isset($imagesById[$imageId])) {
                throw new UnexpectedValueException(sprintf(
                    'Media %s image with ID "%s" was not found.',
                    $type,
                    $imageId
                ));
            }

            $imagesByType[$type] = $imagesById[$imageId];
        }

        return $imagesByType;
    }

    /**
     * @throws Exception
     */
    private function readCountries(int $mediaId): array
    {
        $countryIds = $this->connection
            ->createQueryBuilder()
            ->select('mc.country_id')
            ->from('media_countries', 'mc')
            ->where('mc.media_id = :media_id')
            ->setParameter('media_id', $mediaId, ParameterType::INTEGER)
            ->executeQuery()
            ->fetchFirstColumn();

        $countriesById = $this->countryReader->findByIds($countryIds);
        $countries = [];

        foreach ($countryIds as $countryId) {
            if (!isset($countriesById[$countryId])) {
                throw new UnexpectedValueException(sprintf(
                    'Country with ID "%d" assigned to media "%d" was not found.',
                    $countryId,
                    $mediaId,
                ));
            }

            $countries[] = $countriesById[$countryId];
        }

        return $countries;
    }

    /**
     * @throws DateMalformedStringException
     */
    private function toDateTime(?string $value): ?DateTimeImmutable
    {
        return $value === null ? null : new DateTimeImmutable($value);
    }
}
