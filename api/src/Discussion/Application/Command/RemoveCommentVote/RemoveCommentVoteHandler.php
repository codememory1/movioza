<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Command\RemoveCommentVote;

use Movioza\Discussion\Application\Exception\CommentNotFoundException;
use Movioza\Discussion\Application\Reader\CommentReaderInterface;
use Movioza\Discussion\Domain\Repository\CommentVoteRepositoryInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;

final readonly class RemoveCommentVoteHandler
{
    public function __construct(
        private TransactionManagerInterface $tm,
        private CommentReaderInterface $commentReader,
        private CommentVoteRepositoryInterface $commentVoteRepository
    ) {
    }

    /**
     * Removes the guest's vote if it exists.
     *
     * An absent vote is treated as a successful no-op.
     *
     * @throws CommentNotFoundException When the comment does not exist.
     */
    public function __invoke(RemoveCommentVoteCommand $command): void
    {
        $this->tm->transactional(function () use ($command) {
            if (!$this->commentReader->exists($command->commentId->getValue())) {
                throw new CommentNotFoundException($command->commentId->getValue());
            }

            $commentVote = $this->commentVoteRepository->findByCommentIdAndGuestId(
                $command->commentId,
                $command->guestId
            );

            if ($commentVote !== null) {
                $this->commentVoteRepository->remove($commentVote);
            }
        });
    }
}
