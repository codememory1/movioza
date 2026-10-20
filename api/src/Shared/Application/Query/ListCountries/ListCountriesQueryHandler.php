<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Query\ListCountries;

use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;
use Movioza\Shared\Application\Reader\CountryReaderInterface;
use Movioza\Shared\Application\ReadModel\CountryView;

final readonly class ListCountriesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CountryReaderInterface $countryReader
    ) {
    }

    /**
     * @return list<CountryView>
     */
    public function __invoke(ListCountriesQuery $query): array
    {
        return $this->countryReader->findAll();
    }
}
