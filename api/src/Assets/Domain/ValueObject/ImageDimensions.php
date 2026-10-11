<?php

declare(strict_types = 1);

namespace Movioza\Assets\Domain\ValueObject;

use InvalidArgumentException;
use JsonException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

final readonly class ImageDimensions implements ValueObjectInterface
{
    public function __construct(
        private int $width,
        private int $height,
    ) {
        if ($width <= 0 || $height <= 0) {
            throw new InvalidArgumentException('Image dimensions must be positive.');
        }
    }

    /**
     * @return array{width: int, height: int}
     */
    public function getValue(): array
    {
        return [
            'width' => $this->width,
            'height' => $this->height,
        ];
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function getAspectRatio(): float
    {
        return $this->width / $this->height;
    }

    public function equals(ValueObjectInterface $other): bool
    {
        return $other instanceof self
            && $this->getWidth() === $other->getWidth()
            && $this->getHeight() === $other->getHeight();
    }

    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->getValue(), JSON_THROW_ON_ERROR);
    }
}
