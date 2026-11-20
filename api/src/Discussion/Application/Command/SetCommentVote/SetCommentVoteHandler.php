<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Command\SetCommentVote;

use Movioza\Discussion\Application\Exception\CommentNotFoundException;
use Movioza\Discussion\Application\Reader\CommentReaderInterface;
use Movioza\Discussion\Domain\Entity\CommentVote;
use Movioza\Discussion\Domain\Repository\CommentVoteRepositoryInterface;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;

final readonly class SetCommentVoteHandler implements CommandHandlerInterface
{
    public function __construct(
        private TransactionManagerInterface $tm,
        private CommentReaderInterface $commentReader,
        private CommentVoteRepositoryInterface $commentVoteRepository,
        private ClockInterface $clock
    ) {
    }

    /**
     * Creates a vote or changes the type of the guest's existing vote.
     *
     * @return bool True if a new vote was created; false if a vote already existed.
     *
     * @throws CommentNotFoundException When the comment does not exist.
     */
    public function __invoke(SetCommentVoteCommand $command): bool
    {
        return $this->tm->transactional(function () use ($command): bool {
            if (!$this->commentReader->exists($command->commentId->getValue())) {
                throw new CommentNotFoundException($command->commentId->getValue());
            }

            $commentVote = $this->commentVoteRepository->findByCommentIdAndGuestId(
                $command->commentId,
                $command->guestId
            );
            $now = $this->clock->now();
            $created = $commentVote === null;

            if ($commentVote === null) {
                $commentVote = new CommentVote(
                    $command->commentId,
                    $command->guestId,
                    $command->voteType,
                    $now
                );
            } else {
                $commentVote->changeType($command->voteType, $now);
            }

            $this->commentVoteRepository->save($commentVote);

            return $created;
        });
    }
}
