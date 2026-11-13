<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Messenger\Message;

use Movioza\Shared\Infrastructure\Enum\DebeziumOperation;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Serializer\Attribute\SerializedPath;

final readonly class CountryChangeMessage
{
    public function __construct(
        #[SerializedName('op')]
        public DebeziumOperation $operation,

        #[SerializedPath('[after][id]')]
        public int $id
    ) {
    }
}
