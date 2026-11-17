<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Exception;

use Movioza\Shared\Application\Exception\ApplicationException;

final class ParentCommentNotFoundException extends ApplicationException
{
    public function __construct(
        public readonly int $parentId
    ) {
        parent::__construct(sprintf('Parent comment with ID %d was not found.', $parentId));
    }
}
