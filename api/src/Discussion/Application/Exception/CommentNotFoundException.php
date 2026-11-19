<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Exception;

use Movioza\Shared\Application\Exception\ApplicationException;

final class CommentNotFoundException extends ApplicationException
{
    public function __construct(
        public readonly int $commentId
    ) {
        parent::__construct(sprintf('Comment with ID %d was not found.', $this->commentId));
    }
}
