<?php

declare(strict_types = 1);

namespace Movioza\People\Domain\Repository;

use Movioza\People\Domain\Entity\Person;

interface PersonRepositoryInterface
{
    /**
     * Registers the entity for persistence within the caller-managed transaction boundary.
     */
    public function save(Person $person): void;
}
