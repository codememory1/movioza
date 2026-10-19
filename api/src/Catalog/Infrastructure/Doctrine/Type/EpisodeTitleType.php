<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Infrastructure\Doctrine\Type;

use Movioza\Catalog\Domain\ValueObject\EpisodeTitle;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

class EpisodeTitleType extends AbstractStringValueObjectType
{
    public const string NAME = 'catalog_episode_title';

    protected function getValueObjectClass(): string
    {
        return EpisodeTitle::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
