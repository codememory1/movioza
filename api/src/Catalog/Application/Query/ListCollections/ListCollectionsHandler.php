<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\ListCollections;

use Movioza\Catalog\Application\Pagination\CollectionListCursorPosition;
use Movioza\Catalog\Application\Reader\CollectionReaderInterface;
use Movioza\Shared\Application\Bus\Query\QueryHandlerInterface;
use Movioza\Shared\Application\Pagination\CursorCodecInterface;
use Movioza\Shared\Application\Pagination\CursorPage;

final readonly class ListCollectionsHandler implements QueryHandlerInterface
{
    public function __construct(
        private CursorCodecInterface $cursorCodec,
        private CollectionReaderInterface $reader
    ) {
    }

    public function __invoke(ListCollectionsQuery $query): CursorPage
    {
        $cursorPosition = null;

        if ($query->cursor !== null) {
            $cursorPosition = $this->cursorCodec->decode($query->cursor, CollectionListCursorPosition::class);
        }

        $nextCursor = null;
        $collections = $this->reader->findPaginated($query->limit + 1, $cursorPosition);

        if (count($collections) > $query->limit) {
            array_pop($collections);

            $lastCollection = array_last($collections);
            $nextCursor = $this->cursorCodec->encode(new CollectionListCursorPosition(
                $lastCollection->id,
                $lastCollection->createdAt
            ));
        }

        return new CursorPage($collections, $query->limit, $nextCursor);
    }
}
