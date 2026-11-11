<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\ListGenres;

use Movioza\Catalog\Application\Reader\GenreReaderInterface;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;

final readonly class ListGenresHandler implements QueryHandlerInterface
{
    public function __construct(
        private GenreReaderInterface $reader
    ) {
    }

    public function __invoke(ListGenresQuery $query): array
    {
        return $this->reader->findAll();
    }
}
