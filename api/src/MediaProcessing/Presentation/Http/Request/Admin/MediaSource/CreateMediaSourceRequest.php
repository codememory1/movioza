<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Http\Request\Admin\MediaSource;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateMediaSourceRequest
{
    public function __construct(
        #[Assert\Positive(message: 'Media ID must be a positive integer.')]
        public int $mediaId,

        #[Assert\Sequentially([
            new Assert\NotBlank(
                message: 'Source page URL is required.',
                normalizer: 'trim',
            ),
            new Assert\Url(
                message: 'Source page URL must be a valid HTTP or HTTPS URL.',
                protocols: ['http', 'https'],
            )
        ])]
        public string $pageUrl
    ) {
    }
}
