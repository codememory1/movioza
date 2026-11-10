<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\ListCollections;

use Movioza\Shared\Application\Bus\Query\QueryInterface;

final readonly class ListCollectionsQuery implements QueryInterface
{
    public function __construct(
        public int $limit,
        public ?string $cursor
    ) {
    }
}
