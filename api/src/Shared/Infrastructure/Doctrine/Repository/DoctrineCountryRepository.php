<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Repository;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Movioza\Shared\Application\Reader\CountryReaderInterface;
use Movioza\Shared\Application\ReadModel\CountryView;
use Movioza\Shared\Domain\Entity\Country;
use Movioza\Shared\Domain\Repository\CountryRepositoryInterface;

final readonly class DoctrineCountryRepository implements CountryRepositoryInterface, CountryReaderInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @throws Exception
     */
    public function findAll(): array
    {
        $rows = $this->em->getConnection()->fetchAllAssociative(<<<SQL
        SELECT id, name, code FROM countries ORDER BY name ASC, id ASC
        SQL);

        return array_map(
            static fn (array $row): CountryView =>
            new CountryView($row['id'], $row['name'], $row['code']),
            $rows
        );
    }

    public function save(Country $country): void
    {
        $this->em->persist($country);
    }
}
