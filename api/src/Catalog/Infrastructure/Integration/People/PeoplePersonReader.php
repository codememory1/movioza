<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Integration\People;

use Movioza\Catalog\Application\Reader\ExternalPersonReaderInterface;
use Movioza\People\Application\Reader\PersonReaderInterface;
use Movioza\People\Application\ReadModel\PersonView as PeoplePersonView;
use Movioza\Catalog\Application\ReadModel\PersonView;

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

        return array_map(static fn (PeoplePersonView $person): PersonView => new PersonView(
            $person->id,
            $person->slug,
            $person->name
        ), $persons);
    }
}
