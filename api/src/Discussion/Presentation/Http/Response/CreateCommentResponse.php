<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Response;

use Movioza\Discussion\Domain\ValueObject\CommentID;

final readonly class CreateCommentResponse
{
    public function __construct(
        private CommentID $id
    ) {
    }

    public function toArray(): array
    {
        return [
            'data' => $this->id->getValue()
        ];
    }
}
