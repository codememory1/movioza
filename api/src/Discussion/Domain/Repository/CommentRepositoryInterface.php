<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\Repository;

use Movioza\Discussion\Domain\Entity\Comment;

interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;
}
