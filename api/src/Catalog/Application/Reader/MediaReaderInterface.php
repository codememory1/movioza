<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\Reader;

use Movioza\Catalog\Application\ReadModel\MediaView;
use UnexpectedValueException;

interface MediaReaderInterface
{
    /**
     * Returns complete media details, or null when the media row does not exist.
     *
     * @throws UnexpectedValueException When a required referenced record cannot be resolved.
     */
    public function findByID(int $id): ?MediaView;

    /**
     * Checks whether media with the specified ID exists.
     */
    public function exists(int $id): bool;
}
