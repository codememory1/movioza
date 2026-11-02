<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

/**
 * A named, object-valued member of a response document or metadata object.
 */
interface DocumentMemberInterface
{
    public function getName(): string;

    public function getValue(): object;
}
