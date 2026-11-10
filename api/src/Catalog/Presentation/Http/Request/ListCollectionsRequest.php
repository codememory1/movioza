<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class ListCollectionsRequest
{
    public function __construct(
        #[Assert\Range(
            notInRangeMessage: 'Limit must be between {{ min }} and {{ max }}.',
            min: 1,
            max: 100
        )]
        public int $limit = 20,

        #[Assert\Sequentially([
            new Assert\NotBlank(
                message: 'Cursor must not be empty.',
                allowNull: true
            ),
            new Assert\Length(
                max: 4096,
                countUnit: Assert\Length::COUNT_BYTES,
                maxMessage: 'Cursor must not exceed {{ limit }} bytes.'
            ),
            new Assert\Regex(
                pattern: '/\A[A-Za-z0-9_-]+\.[A-Za-z0-9_-]+\z/',
                message: 'Cursor must contain two Base64URL-encoded parts separated by a dot.'
            )
        ])]
        public ?string $cursor = null
    ) {
    }
}
