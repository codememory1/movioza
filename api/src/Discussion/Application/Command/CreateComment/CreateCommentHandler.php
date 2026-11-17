<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Command\CreateComment;

use Movioza\Discussion\Application\Exception\MediaNotFoundException;
use Movioza\Discussion\Application\Exception\ParentCommentNotFoundException;
use Movioza\Discussion\Application\Media\MediaExistenceCheckerInterface;
use Movioza\Discussion\Domain\Entity\Comment;
use Movioza\Discussion\Domain\Repository\CommentRepositoryInterface;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;

final readonly class CreateCommentHandler implements CommandHandlerInterface
{
    public function __construct(
        private MediaExistenceCheckerInterface $mediaExistenceChecker,
        private TransactionManagerInterface $tm,
        private CommentRepositoryInterface $commentRepository,
        private ClockInterface $clock
    ) {
    }

    public function __invoke(CreateCommentCommand $command): void
    {
        if (!$this->mediaExistenceChecker->exists($command->mediaId->getValue())) {
            throw new MediaNotFoundException($command->mediaId->getValue());
        }

        $parentComment = null;

        if ($command->parentId !== null) {
            $parentComment = $this->commentRepository->findById($command->parentId);

            if ($parentComment === null) {
                throw new ParentCommentNotFoundException($command->parentId->getValue());
            }
        }

        $this->tm->transactional(function () use ($command, $parentComment) {
            $this->commentRepository->save(new Comment(
                $command->id,
                $command->mediaId,
                $command->author->guestId,
                $parentComment,
                $command->author->authorName,
                $command->text,
                $this->clock->now()
            ));
        });
    }
}
