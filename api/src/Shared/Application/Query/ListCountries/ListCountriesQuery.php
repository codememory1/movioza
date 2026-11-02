<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Query\ListCountries;

use Movioza\Shared\Application\Bus\Query\QueryInterface;
use Movioza\Shared\Application\ReadModel\CountryView;

/**
 * @implements QueryInterface<list<CountryView>>
 */
final class ListCountriesQuery implements QueryInterface
{
}
