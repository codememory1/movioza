<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Query\ListMediaComments;

use Movioza\Discussion\Domain\ValueObject\MediaID;
use Movioza\Shared\Application\Bus\Query\QueryInterface;

final readonly class ListMediaCommentsQuery implements QueryInterface
{
    public function __construct(
        public MediaID $mediaId,
        public int $limit,
        public ?string $cursor = null
    ) {
    }
}
