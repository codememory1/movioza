<?php

declare(strict_types=1);

namespace Movioza\Dto\Admin\MediaSource;

use Symfony\Component\Validator\Constraints as Assert;

readonly class CreateMediaSourceDto
{
    public function __construct(
        #[Assert\NotBlank(message: 'Torrent tracker URL is required.')]
        #[Assert\Url(message: 'Please provide a valid URL.')]
        public string $torrentTrackerUrl
    ) {
    }
}
