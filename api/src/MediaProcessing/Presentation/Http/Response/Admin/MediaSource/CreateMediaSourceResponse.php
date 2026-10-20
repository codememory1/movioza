<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Http\Response\Admin\MediaSource;

use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;

final readonly class CreateMediaSourceResponse
{
    public function __construct(
        private MediaSourceID $ID,
    ) {
    }

    public function toArray(): array
    {
        return [
            'data' => [
                'id' => $this->ID->getValue(),
            ]
        ];
    }
}
