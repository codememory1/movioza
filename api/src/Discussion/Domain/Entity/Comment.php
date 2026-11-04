<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Domain\Entity;

use DateTimeImmutable;
use InvalidArgumentException;
use Movioza\Discussion\Domain\ValueObject\AuthorName;
use Movioza\Discussion\Domain\ValueObject\CommentID;
use Movioza\Discussion\Domain\ValueObject\CommentText;
use Movioza\Discussion\Domain\ValueObject\GuestID;
use Movioza\Discussion\Domain\ValueObject\MediaID;
use Movioza\Shared\Domain\Aggregate\AggregateRoot;

final class Comment extends AggregateRoot
{
    private ?CommentID $parentId = null;

    public function __construct(
        private readonly CommentID $id,
        private readonly MediaID $mediaId,
        private readonly GuestID $guestId,
        ?Comment $parent,
        private readonly AuthorName $authorName,
        private readonly CommentText $comment,
        private readonly DateTimeImmutable $createdAt,
    ) {
        if ($parent !== null) {
            if ($parent->getId()->equals($id)) {
                throw new InvalidArgumentException('A comment cannot be its own parent.');
            }

            if (!$parent->getMediaId()->equals($mediaId)) {
                throw new InvalidArgumentException('A reply must belong to the same media as its parent comment.');
            }

            $this->parentId = $parent->id;
        }
    }

    public function getId(): CommentID
    {
        return $this->id;
    }

    public function getMediaId(): MediaID
    {
        return $this->mediaId;
    }

    public function getGuestId(): GuestID
    {
        return $this->guestId;
    }

    public function getParentId(): ?CommentID
    {
        return $this->parentId;
    }

    public function getAuthorName(): AuthorName
    {
        return $this->authorName;
    }

    public function getComment(): CommentText
    {
        return $this->comment;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
