<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

interface DocumentMemberInterface
{
    public function getName(): string;

    public function getValue(): object;
}
