<?php

declare(strict_types = 1);

namespace Movioza\People\Domain\Repository;

use Movioza\People\Domain\Entity\Person;

interface PersonRepositoryInterface
{
    public function save(Person $person): void;
}
