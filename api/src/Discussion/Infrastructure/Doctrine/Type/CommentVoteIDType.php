<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Type;

use Movioza\Discussion\Domain\ValueObject\CommentVoteID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractBigIntegerValueObjectType;

final class CommentVoteIDType extends AbstractBigIntegerValueObjectType
{
    private const string NAME = 'discussion_comment_vote_id';

    protected function getValueObjectClass(): string
    {
        return CommentVoteID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
