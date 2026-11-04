<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Type;

use Movioza\Discussion\Domain\ValueObject\CommentText;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class CommentTextType extends AbstractStringValueObjectType
{
    private const string NAME = 'discussion_comment_text';

    protected function getValueObjectClass(): string
    {
        return CommentText::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
