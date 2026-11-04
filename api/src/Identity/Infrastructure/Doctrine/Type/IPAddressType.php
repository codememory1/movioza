<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Exception\ValueNotConvertible;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Movioza\Identity\Domain\ValueObject\IPAddress;

final class IPAddressType extends Type
{
    public const string NAME = 'identity_ip';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'INET';
    }

    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!$value instanceof IpAddress) {
            throw InvalidType::new($value, self::NAME, ['null', IpAddress::class]);
        }

        return $value->getValue();
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?IpAddress
    {
        if ($value === null || $value instanceof IpAddress) {
            return $value;
        }

        if (!is_string($value)) {
            throw InvalidType::new($value, self::NAME, ['null', 'string', IpAddress::class]);
        }

        try {
            return new IpAddress($value);
        } catch (InvalidArgumentException $exception) {
            throw ValueNotConvertible::new(
                $value,
                self::NAME,
                $exception->getMessage(),
                $exception
            );
        }
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return ['inet'];
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
