<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Presentation\Http\Request;

use Movioza\Discussion\Domain\ValueObject\AuthorName;
use Movioza\Discussion\Domain\ValueObject\CommentText;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateCommentRequest
{
    public function __construct(
        #[Assert\NotBlank(
            message: 'Author name is required.',
            normalizer: 'trim'
        )]
        #[Assert\Length(
            max: AuthorName::MAX_LENGTH,
            maxMessage: 'Author name must not exceed {{ limit }} characters.'
        )]
        public string $authorName,

        #[Assert\NotBlank(
            message: 'Comment text is required.',
            normalizer: 'trim'
        )]
        #[Assert\Length(
            max: CommentText::MAX_LENGTH,
            maxMessage: 'Comment text must not exceed {{ limit }} characters.'
        )]
        public string $text,

        #[Assert\Positive(message: 'Parent comment ID must be greater than zero.')]
        public ?int $parentId = null
    ) {
    }
}
