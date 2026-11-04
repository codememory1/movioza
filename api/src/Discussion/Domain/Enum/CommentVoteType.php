<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\Enum;

enum CommentVoteType: int
{
    case LIKE = 1;
    case DISLIKE = -1;
}
