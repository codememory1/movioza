<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Type;

use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class CommentIDType extends AbstractBigIntegerValueObjectType
{
    private const string NAME = 'discussion_comment_id';

    protected function getValueObjectClass(): string
    {
        return CommentID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
