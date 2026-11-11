<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\ListMediaSeasons;

use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Shared\Application\Bus\Query\QueryInterface;

final readonly class ListMediaSeasonsQuery implements QueryInterface
{
    public function __construct(
        public MediaID $mediaID
    ) {
    }
}
