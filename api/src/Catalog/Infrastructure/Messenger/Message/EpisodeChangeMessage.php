<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Messenger\Message;

use Movioza\Shared\Infrastructure\Enum\DebeziumOperation;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Serializer\Attribute\SerializedPath;

final readonly class EpisodeChangeMessage
{
    public function __construct(
        #[SerializedName('op')]
        public DebeziumOperation $operation,

        #[SerializedPath('[after][id]')]
        public int $id,

        #[SerializedPath('[after][season_id]')]
        public int $seasonId
    ) {
    }
}
