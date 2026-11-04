<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\Entity;

use DateTimeImmutable;
use Movioza\Discussion\Domain\Enum\CommentVoteType;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\CommentVoteID;
use Movioza\Discussion\Domain\ValueObject\GuestID;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;

final class CommentVote extends AggregateRoot
{
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(
        private readonly CommentVoteID $id,
        private readonly CommentID $commentId,
        private readonly GuestID $guestId,
        private CommentVoteType $type,
        private readonly DateTimeImmutable $createdAt
    ) {
    }

    public function changeType(CommentVoteType $type, DateTimeImmutable $changedAt): void
    {
        if ($this->type === $type) {
            return;
        }

        $this->type = $type;
        $this->updatedAt = $changedAt;
    }

    public function getId(): CommentVoteID
    {
        return $this->id;
    }

    public function getCommentId(): CommentID
    {
        return $this->commentId;
    }

    public function getGuestId(): GuestID
    {
        return $this->guestId;
    }

    public function getType(): CommentVoteType
    {
        return $this->type;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
