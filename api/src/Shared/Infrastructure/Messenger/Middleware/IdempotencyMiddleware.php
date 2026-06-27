<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Messenger\Middleware;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Movioza\Shared\Application\Bus\Command\IdempotentCommandInterface;
use Movioza\Shared\Domain\Inbox\InboxMessage;
use Movioza\Shared\Domain\Inbox\InboxMessageRepositoryInterface;
use Movioza\Shared\Domain\Uuid\UuidGeneratorInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use Throwable;

readonly class IdempotencyMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly UuidGeneratorInterface $uuidGenerator,
        private InboxMessageRepositoryInterface $inboxMessageRepository
    ) {
    }

    /**
     * @throws ExceptionInterface
     * @throws Throwable
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if (!$message instanceof IdempotentCommandInterface) {
            return $stack->next()->handle($envelope, $stack);
        }

        if ($envelope->last(ReceivedStamp::class) === null) {
            return $stack->next()->handle($envelope, $stack);
        }

        try {
            $this->inboxMessageRepository->add(new InboxMessage(
                $this->uuidGenerator->generate(),
                $message->getIdempotentKey()
            ));
        } catch (UniqueConstraintViolationException) {
            if (!$this->markAsProcessingIfFailed($message)) {
                return $envelope;
            }
        }

        try {
            $result = $stack->next()->handle($envelope, $stack);

            $this->inboxMessageRepository->markAsProcessed($message->getIdempotentKey());

            return $result;
        } catch (Throwable $e) {
            $this->inboxMessageRepository->markAsFailed($message->getIdempotentKey());

            throw $e;
        }
    }

    private function markAsProcessingIfFailed(IdempotentCommandInterface $message): bool
    {
        return $this->inboxMessageRepository->markAsProcessing($message->getIdempotentKey());
    }
}
