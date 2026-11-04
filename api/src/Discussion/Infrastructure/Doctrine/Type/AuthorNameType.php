<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Type;

use Movioza\Discussion\Domain\ValueObject\AuthorName;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class AuthorNameType extends AbstractStringValueObjectType
{
    private const string NAME = 'discussion_author_name';

    protected function getValueObjectClass(): string
    {
        return AuthorName::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
