<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Request;

use Movioza\Discussion\Domain\Enum\CommentVoteType;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class SetCommentVoteRequest
{
    public function __construct(
        #[Assert\Choice(
            callback: [CommentVoteType::class, 'values'],
            message: 'Vote type must be one of the following values: {{ choices }}.'
        )]
        public int $type
    ) {
    }
}
