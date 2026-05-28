<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

interface MediaCountryInterface
{
    public MediaInterface $media {
        get;
    }

    public CountryInterface $country {
        get;
    }
}
