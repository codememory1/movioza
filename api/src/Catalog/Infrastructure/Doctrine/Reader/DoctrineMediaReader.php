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
use Movioza\Catalog\Application\Reader\ExternalPersonReaderInterface;
use Movioza\Catalog\Application\Reader\GenreReaderInterface;
use Movioza\Catalog\Application\Reader\MediaReaderInterface;
use Movioza\Catalog\Application\ReadModel\GenreView;
use Movioza\Catalog\Application\ReadModel\ImageView;
use Movioza\Catalog\Application\ReadModel\MediaView;
use Movioza\Catalog\Application\ReadModel\PersonView;
use Movioza\Catalog\Domain\Enum\MediaImageType;
use Movioza\Catalog\Domain\Enum\MediaKind;
use Movioza\Catalog\Domain\Enum\MediaPersonRole;
use Movioza\Catalog\Domain\Enum\MediaType;
use Movioza\Shared\Application\Reader\CountryReaderInterface;
use Movioza\Shared\Application\ReadModel\CountryView;
use UnexpectedValueException;

final readonly class DoctrineMediaReader implements MediaReaderInterface
{
    public function __construct(
        private Connection $connection,
        private ExternalImageReaderInterface $externalImageReader,
        private CountryReaderInterface $countryReader,
        private GenreReaderInterface $genreReader,
        private ExternalPersonReaderInterface $externalPersonReader
    ) {
    }

    /**
     * Builds media details from Catalog data and referenced read models.
     * Returns null only when the media row itself is absent.
     *
     * @throws DateMalformedStringException
     * @throws Exception
     * @throws UnexpectedValueException When a required referenced record is missing.
     */
    public function findByID(int $id): ?MediaView
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
        $genres = $this->readGenres($id);
        $persons = $this->readPersons($id);

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
            $genres,
            $persons[MediaPersonRole::DIRECTOR->value] ?? [],
            $persons[MediaPersonRole::ACTOR->value] ?? [],
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
     * Resolves the logo, poster, and backdrop required by the media details response.
     *
     * @return array<string, ImageView> Images keyed by MediaImageType values.
     * @throws Exception
     * @throws UnexpectedValueException When an assignment or its referenced image is missing.
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

        $imagesById = $this->externalImageReader->findByIDs(
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
     * Resolves country assignments into a sequential list.
     *
     * @return list<CountryView>
     * @throws Exception
     * @throws UnexpectedValueException When an assigned country cannot be resolved.
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
     * Resolves genre assignments into a sequential list.
     *
     * @return list<GenreView>
     * @throws Exception
     * @throws UnexpectedValueException When an assigned genre cannot be resolved.
     */
    private function readGenres(int $mediaId): array
    {
        $genreIds = $this->connection
            ->createQueryBuilder()
            ->select('gm.genre_id')
            ->from('media_genres', 'gm')
            ->where('gm.media_id = :media_id')
            ->setParameter('media_id', $mediaId, ParameterType::INTEGER)
            ->executeQuery()
            ->fetchFirstColumn();

        $genresById = $this->genreReader->findByIDs($genreIds);
        $genres = [];

        foreach ($genreIds as $genreId) {
            if (!isset($genresById[$genreId])) {
                throw new UnexpectedValueException(sprintf(
                    'Genre with ID "%d" assigned to media "%d" was not found.',
                    $genreId,
                    $mediaId,
                ));
            }

            $genres[] = $genresById[$genreId];
        }

        return $genres;
    }

    /**
     * Resolves each distinct person once and groups person views by media role.
     * A person assigned to multiple roles appears in each corresponding group.
     *
     * @return array<string, list<PersonView>>
     * @throws Exception
     * @throws UnexpectedValueException When an assigned person cannot be resolved.
     */
    private function readPersons(int $mediaId): array
    {
        $mediaPersons = $this->connection
            ->createQueryBuilder()
            ->select('mp.person_id', 'mp.role')
            ->from('media_persons', 'mp')
            ->where('mp.media_id = :media_id')
            ->setParameter('media_id', $mediaId, ParameterType::INTEGER)
            ->executeQuery()
            ->fetchAllAssociative();

        if ($mediaPersons === []) {
            return [];
        }

        // Deduplicate person lookups only; retain every assignment to preserve multiple roles.
        $mediaPersonIDs = array_unique(array_column($mediaPersons, 'person_id'));
        $personsByID = $this->externalPersonReader->findByIDs($mediaPersonIDs);
        $personsByRole = [];

        // Group the original assignments, not the deduplicated person result.
        foreach ($mediaPersons as $mediaPerson) {
            $personID = $mediaPerson['person_id'];

            if (!isset($personsByID[$personID])) {
                throw new UnexpectedValueException(sprintf(
                    'Person with ID "%d" assigned to media "%d" was not found.',
                    $personID,
                    $mediaId,
                ));
            }

            $personsByRole[$mediaPerson['role']][] = $personsByID[$personID];
        }

        return $personsByRole;
    }

    /**
     * @throws DateMalformedStringException
     */
    private function toDateTime(?string $value): ?DateTimeImmutable
    {
        return $value === null ? null : new DateTimeImmutable($value);
    }
}
