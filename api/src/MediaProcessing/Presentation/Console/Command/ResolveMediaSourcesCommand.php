<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Console\Command;

use Movioza\MediaProcessing\Application\Command\ResolveMediaSource\ResolveMediaSourceCommand;
use Movioza\MediaProcessing\Application\Reader\MediaSourceReaderInterface;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Presentation\Console\Input\WorkerOptions;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\MapInput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\ResetInterface;
use Throwable;
use const SIGINT;
use const SIGTERM;

#[AsCommand(
    name: 'movioza:media-sources:resolve',
    description: 'Resolve media sources',
)]
final class ResolveMediaSourcesCommand implements SignalableCommandInterface
{
    private bool $shouldStop = false;

    public function __construct(
        private readonly MediaSourceReaderInterface $mediaSourceReader,
        private CommandBusInterface $bus,
        private readonly LoggerInterface $logger,
        #[Autowire(service: 'services_resetter')]
        private readonly ResetInterface $reset
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[MapInput] WorkerOptions $options
    ): int {
        if (!$options->validate($io)) {
            return Command::INVALID;
        }

        $this->shouldStop = false;
        $processedCount = 0;

        $io->info('Media source resolver started.');

        try {
            while (!$this->shouldStop) {
                $currentSourceId  = null;
                $mediaSourceIDs = $this->mediaSourceReader->findPendingIDs($options->batchSize);

                if ($mediaSourceIDs === []) {
                    if ($options->once || $this->shouldStop) {
                        break;
                    }

                    sleep($options->sleep);

                    continue;
                }

                foreach ($mediaSourceIDs as $mediaSourceID) {
                    if ($this->shouldStop) {
                        break;
                    }

                    $currentSourceId = $mediaSourceID;

                    try {
                        $this->bus->dispatch(new ResolveMediaSourceCommand(
                            new MediaSourceID($mediaSourceID)
                        ));
                    } finally {
                        $this->reset->reset();
                    }

                    ++$processedCount;

                    $io->writeln(
                        sprintf('Processed media source #%d.', $mediaSourceID),
                        OutputInterface::VERBOSITY_VERBOSE
                    );
                }

                if ($options->once) {
                    break;
                }
            }
        } catch (Throwable $e) {
            $this->logger->error(
                'Media source resolver stopped because of an unexpected error.',
                [
                    'media_source_id' => $currentSourceId,
                    'exception' => $e
                ]
            );

            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success(sprintf(
            'Media source resolver stopped. Processed sources: %d.',
            $processedCount
        ));

        return Command::SUCCESS;
    }

    public function getSubscribedSignals(): array
    {
        return [SIGTERM, SIGINT];
    }

    public function handleSignal(int $signal, false|int $previousExitCode = 0): int|false
    {
        $this->shouldStop = true;

        return false;
    }
}
