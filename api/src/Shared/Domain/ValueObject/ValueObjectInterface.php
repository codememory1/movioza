<?php

declare(strict_types = 1);

namespace Movioza\Shared\Domain\ValueObject;

use Stringable;

interface ValueObjectInterface extends Stringable
{
    public function getValue(): mixed;

    public function equals(ValueObjectInterface $other): bool;
}
