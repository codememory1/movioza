<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\Repository;

use Movioza\Discussion\Domain\Entity\Comment;
use Movioza\Discussion\Domain\ValueObject\CommentID;

interface CommentRepositoryInterface
{
    public function findById(CommentID $id): ?Comment;

    public function save(Comment $comment): void;
}
