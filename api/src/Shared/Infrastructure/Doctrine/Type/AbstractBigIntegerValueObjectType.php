<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

abstract class AbstractBigIntegerValueObjectType extends AbstractIntegerValueObjectType
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getBigIntTypeDeclarationSQL($column);
    }

    /**
     * Binds BIGINT values as strings at the DBAL parameter boundary.
     * The value object still represents its value as a PHP integer.
     */
    public function getBindingType(): ParameterType
    {
        return ParameterType::STRING;
    }
}
