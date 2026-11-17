<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\Exception;

use Movioza\Shared\Application\Exception\ApplicationException;

final class ParentCommentMediaMismatchException extends ApplicationException
{
    public function __construct(
        public readonly int $parentId,
        public readonly int $mediaId
    ) {
        parent::__construct(sprintf(
            'Parent comment with ID %d does not belong to media with ID %d.',
            $parentId,
            $mediaId
        ));
    }
}
