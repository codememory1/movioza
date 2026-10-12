<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\SerializationFailed;
use Doctrine\DBAL\Types\JsonType;
use Movioza\Catalog\Domain\ValueObject\MediaTitle;
use UnexpectedValueException;

final class MediaTitlesType extends JsonType
{
    public const string NAME = 'catalog_media_titles';

    /**
     * @throws SerializationFailed
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if (!is_array($value) || !array_is_list($value)) {
            throw new UnexpectedValueException(sprintf('%s expects a list of MediaTitle objects.', self::NAME));
        }

        $titles = [];

        foreach ($value as $title) {
            if (!$title instanceof MediaTitle) {
                throw new UnexpectedValueException(sprintf('Each %s element must be a MediaTitle.', self::NAME));
            }

            $titles[] = $title->getValue();
        }

        return parent::convertToDatabaseValue($titles, $platform);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?array
    {
        if ($value === null) {
            return null;
        }

        $values = parent::convertToPHPValue($value, $platform);

        if (!is_array($values) || !array_is_list($values)) {
            throw new UnexpectedValueException(sprintf('%s must contain a JSON array of strings.', self::NAME));
        }

        $titles = [];

        foreach ($values as $title) {
            if (!is_string($title)) {
                throw new UnexpectedValueException(sprintf('Each stored %s element must be a string.', self::NAME));
            }

            $titles[] = new MediaTitle($title);
        }

        return $titles;
    }
}
