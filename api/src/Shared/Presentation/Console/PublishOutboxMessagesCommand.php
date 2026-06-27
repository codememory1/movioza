<?php

declare(strict_types = 1);

namespace Movioza\Shared\Presentation\Console;

use InvalidArgumentException;
use Movioza\Shared\Application\Bus\Command\Exception\CouldNotDispatchCommandException;
use Movioza\Shared\Application\Outbox\Exception\CouldNotDeserializeOutboxCommandException;
use Movioza\Shared\Domain\Outbox\OutboxMessage;
use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Application\Outbox\OutboxCommandSerializerInterface;
use Movioza\Shared\Domain\Outbox\OutboxMessageRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

#[AsCommand(
    name: 'movioza:worker:publish-outbox-messages',
    description: 'Publish pending outbox messages',
)]
class PublishOutboxMessagesCommand extends AbstractWorkerCommand
{
    public function __construct(
        private readonly OutboxMessageRepositoryInterface $outboxMessageRepository,
        private readonly OutboxCommandSerializerInterface $outboxCommandSerializer,
        private readonly CommandBusInterface $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'limit',
            null,
            InputOption::VALUE_REQUIRED,
            'Maximum number of outbox messages to process per iteration.',
            50
        );

        parent::configure();
    }

    protected function process(InputInterface $input, OutputInterface $output): void
    {
        $limit = $this->getLimit($input);

        while (true) {
            $outboxMessages = $this->outboxMessageRepository->claimForProcessing($limit);

            if ($outboxMessages === []) {
                break;
            }

            foreach ($outboxMessages as $outboxMessage) {
                try {
                    $this->dispatch($outboxMessage);

                    $this->outboxMessageRepository->markAsSent($outboxMessage->getId());
                } catch (Throwable $e) {
                    $this->outboxMessageRepository->markAsFailed($outboxMessage->getId(), $e->getMessage());
                }
            }
        }
    }

    private function getLimit(InputInterface $input): int
    {
        $option = filter_var($input->getOption('limit'), FILTER_VALIDATE_INT);

        if ($option === false || $option < 0) {
            throw new InvalidArgumentException('The "limit" option must be a positive integer.');
        }

        return $option;
    }

    /**
     * @throws CouldNotDispatchCommandException
     * @throws CouldNotDeserializeOutboxCommandException
     */
    private function dispatch(OutboxMessage $outboxMessage): void
    {
        $this->commandBus->dispatch($this->outboxCommandSerializer->deserialize(
            $outboxMessage->getMessageClass(),
            $outboxMessage->getPayload()
        ));
    }
}
