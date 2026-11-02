<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Query\GetMedia;

use Movioza\Catalog\Application\ReadModel\MediaView;
use Movioza\Catalog\Domain\ValueObject\MediaID;
use Movioza\Shared\Application\Bus\Query\QueryInterface;

/**
 * @implements QueryInterface<MediaView>
 */
final readonly class GetMediaQuery implements QueryInterface
{
    public function __construct(
        public MediaID $id
    ) {
    }
}
