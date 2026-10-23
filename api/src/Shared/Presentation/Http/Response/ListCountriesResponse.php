<?php

declare(strict_types = 1);

namespace Movioza\Shared\Presentation\Http\Response;

use Movioza\Shared\Application\ReadModel\CountryView;

final readonly class ListCountriesResponse
{
    /**
     * @param list<CountryView> $countries
     */
    public function __construct(
        private array $countries
    ) {
    }

    public function toArray(): array
    {
        return array_map(static fn (CountryView $country): array => [
            'id' => $country->id,
            'name' => $country->name,
            'code' => $country->code,
        ], $this->countries);
    }
}
