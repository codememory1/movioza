<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\EpisodeNumber;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractIntegerValueObjectType;

class EpisodeNumberType extends AbstractIntegerValueObjectType
{
    public const string NAME = 'catalog_episode_number';

    protected function getValueObjectClass(): string
    {
        return EpisodeNumber::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
