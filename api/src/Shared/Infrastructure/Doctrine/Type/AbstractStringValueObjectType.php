<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Movioza\Shared\Domain\ValueObject\ValueObjectInterface;

abstract class AbstractStringValueObjectType extends Type
{
    /** @return class-string<ValueObjectInterface> */
    abstract protected function getValueObjectClass(): string;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ValueObjectInterface) {
            return $value->getValue();
        }

        return (string) $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ValueObjectInterface
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ValueObjectInterface) {
            return $value;
        }

        if (!is_int($value) && !is_string($value)) {
            throw new InvalidArgumentException('Expected int or an integer string.');
        }

        $integer = filter_var($value, FILTER_VALIDATE_INT);

        if ($integer === false) {
            throw new InvalidArgumentException('Invalid integer value.');
        }

        $className = $this->getValueObjectClass();

        return new $className($value);
    }
}
