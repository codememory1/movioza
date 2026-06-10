<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;

readonly class TargetFilePath implements Stringable
{
    public string $value;

    public function __construct(string $value)
    {
        $dir = dirname($value);

        if (!is_dir($dir) || !is_writable($dir)) {
            throw new InvalidArgumentException(sprintf('Directory "%s" does not exist or is not writable.', $dir));
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
