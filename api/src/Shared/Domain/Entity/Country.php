<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\Entity;

use Movioza\Shared\Domain\ValueObject\CountryID;
use Movioza\Shared\Domain\ValueObject\CountryName;
use Movioza\Shared\Domain\ValueObject\CountryCode;

/**
 * Represents a country with its identifier, name, and code.
 */
readonly class Country
{
    public function __construct(
        private CountryID $id,
        private CountryName $name,
        private CountryCode $code
    ) {
    }

    /**
     * Returns the country's identifier.
     */
    public function getId(): CountryID
    {
        return $this->id;
    }

    /**
     * Returns the country's name.
     */
    public function getName(): CountryName
    {
        return $this->name;
    }

    /**
     * Returns the country's code.
     */
    public function getCode(): CountryCode
    {
        return $this->code;
    }
}
