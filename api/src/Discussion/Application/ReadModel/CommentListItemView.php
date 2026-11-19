<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\ReadModel;

use DateTimeImmutable;

final readonly class CommentListItemView
{
    public function __construct(
        public int $id,
        public ?int $parentId,
        public string $authorName,
        public string $comment,
        public int $replyCount,
        public DateTimeImmutable $createdAt
    ) {
    }
}
