<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Movioza\MediaProcessing\Domain\ValueObject\LeaseToken;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class LeaseTokenType extends AbstractStringValueObjectType
{
    public const string NAME = 'media_processing_lease_token';

    protected function getValueObjectClass(): string
    {
        return LeaseToken::class;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['length'] = LeaseToken::MAX_LENGTH;

        return parent::getSQLDeclaration($column, $platform);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
