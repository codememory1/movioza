<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Integration\People;

use Movioza\Catalog\Application\Reader\ExternalPersonReaderInterface;
use Movioza\People\Application\Reader\PersonReaderInterface;
use Movioza\People\Application\ReadModel\PersonView as PeoplePersonView;
use Movioza\Catalog\Application\ReadModel\PersonView;

/**
 * Maps People read models to Catalog person projections.
 * Media roles are resolved from Catalog assignments by the caller.
 */
final readonly class PeoplePersonReader implements ExternalPersonReaderInterface
{
    public function __construct(
        private PersonReaderInterface $personReader
    ) {
    }

    public function findByIDs(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        $persons = $this->personReader->findByIds($ids);

        // A single-array map preserves the person-ID keys returned by People.
        return array_map(static fn (PeoplePersonView $person): PersonView => new PersonView(
            $person->id,
            $person->slug,
            $person->name
        ), $persons);
    }
}
