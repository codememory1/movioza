<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Reader;

use Movioza\Shared\Application\ReadModel\CountryView;

interface CountryReaderInterface
{
    /**
     * @return list<CountryView>
     */
    public function findAll(): array;
}
