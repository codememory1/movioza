<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Repository;

use Movioza\Shared\Domain\Entity\Country;

/**
 * Defines persistence operations for countries.
 */
interface CountryRepositoryInterface
{
    /**
     * Updates an existing country.
     *
     * @return bool Whether the update succeeded.
     */
    public function update(Country $country): bool;

    /**
     * Persists a country.
     */
    public function save(Country $country): void;
}
