<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Type;

use Movioza\Shared\Domain\ValueObject\Slug;

class SlugType extends AbstractStringValueObjectType
{
    public const string NAME = 'shared_slug';

    protected function getValueObjectClass(): string
    {
        return Slug::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
