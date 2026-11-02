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
     * Registers the entity for persistence within the caller-managed transaction boundary.
     */
    public function save(Country $country): void;
}
