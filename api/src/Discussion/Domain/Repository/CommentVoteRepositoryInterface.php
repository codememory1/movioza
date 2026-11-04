<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\Repository;

use Movioza\Discussion\Domain\Entity\CommentVote;

interface CommentVoteRepositoryInterface
{
    public function save(CommentVote $commentVote): void;
}
