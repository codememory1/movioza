<?php

declare(strict_types=1);

namespace Movioza\Command;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Movioza\Entity\Interfaces\OutboxMessageInterface;
use Movioza\Factory\Interfaces\OutboxMessageFactoryInterface;
use Movioza\Repository\Interfaces\OutboxMessageRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

#[AsCommand(
    name: 'movioza:worker:publish-outbox-messages',
    description: 'A worker that publishes all outbox messages'
)]
class PublishOutboxMessagesCommand extends AbstractWorkerCommand
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly OutboxMessageRepositoryInterface $outboxMessageRepository,
        private readonly EntityManagerInterface $em,
        private readonly MessageBusInterface $messageBus,
        private readonly OutboxMessageFactoryInterface $outboxMessageFactory
    ) {
        parent::__construct($logger);
    }

    /**
     * @throws Exception
     */
    protected function process(InputInterface $input, OutputInterface $output): void
    {
        /** @var OutboxMessageInterface[] $messages */
        $messages = $this->outboxMessageRepository->claimBatch(100);

        foreach ($messages as $message) {
            $this->handleMessage($message);
        }

        $this->em->clear();
    }

    /**
     * @throws Exception
     */
    private function handleMessage(OutboxMessageInterface $message): void
    {
        try {
            $this->messageBus->dispatch($this->outboxMessageFactory->reconstitute($message));

            $this->outboxMessageRepository->markAsSent($message->id);
        } catch (Throwable $e) {
            $this->outboxMessageRepository->markAsFailed($message->id, $e->getMessage());

            $this->logger->error('Failed to publish Outbox Message.', [
                'exception' => $e,
                'outbox_message_id' => $message->id->toString(),
            ]);
        }
    }
}
