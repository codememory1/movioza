<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\EpisodeID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class EpisodeIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_episode_id';

    protected function getValueObjectClass(): string
    {
        return EpisodeID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
