<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Doctrine\Common\Collections\Collection;

interface CountryInterface extends BigintIdentifierInterface
{
    public string $name {
        get;
    }

    public string $code {
        get;
    }

    /**
     * @var Collection<int, MediaCountryInterface>
     */
    public Collection $mediaCountries {
        get;
    }
}
