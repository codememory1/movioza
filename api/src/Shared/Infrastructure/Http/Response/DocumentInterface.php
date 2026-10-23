<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

use JsonSerializable;

interface DocumentInterface extends JsonSerializable
{
    public function jsonSerialize(): object;
}
