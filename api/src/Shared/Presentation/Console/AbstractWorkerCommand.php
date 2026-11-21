<?php

declare(strict_types = 1);

namespace Movioza\Shared\Presentation\Console;

use Movioza\Shared\Presentation\Console\Input\WorkerOptions;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Service\ResetInterface;
use Throwable;

/**
 * Provides a polling loop for workers that process items by identifier.
 *
 * @template TId of int|string
 */
abstract class AbstractWorkerCommand implements SignalableCommandInterface
{
    private bool $shouldStop = false;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ResetInterface $servicesResetter
    ) {
    }

    /**
     * Runs the worker until a stop is requested, one batch is handled,
     * or an unexpected error occurs.
     */
    final protected function run(SymfonyStyle $io, WorkerOptions $options): int
    {
        $this->shouldStop = false;

        if (!$options->validate($io)) {
            return Command::INVALID;
        }

        $workerName = $this->getWorkerName();
        $handledCount = 0;

        $io->info(sprintf('%s started.', $workerName));

        try {
            while (!$this->shouldStop) {
                $currentItemId = null;
                $ids = $this->fetchIds($options->batchSize);

                if ($ids === []) {
                    // Reset services even when polling finds no work.
                    $this->servicesResetter->reset();

                    if ($options->once || $this->shouldStop) {
                        break;
                    }

                    $this->wait($options->sleep);

                    continue;
                }

                foreach ($ids as $id) {
                    if ($this->shouldStop) {
                        break;
                    }

                    $currentItemId = $id;

                    try {
                        $this->processId($id);
                    } finally {
                        // Release per-item service state before processing another item.
                        $this->servicesResetter->reset();
                    }

                    ++$handledCount;

                    $io->writeln(
                        sprintf('Handled item #%s.', $id),
                        OutputInterface::VERBOSITY_VERBOSE
                    );
                }

                if ($options->once) {
                    break;
                }
            }
        } catch (Throwable $e) {
            $this->logger->error(
                'Worker stopped because of an unexpected error.',
                [
                    'worker' => static::class,
                    'item_id' => $currentItemId,
                    'exception' => $e,
                ]
            );

            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success(sprintf(
            '%s stopped. Handled items: %d.',
            $workerName,
            $handledCount
        ));

        return Command::SUCCESS;
    }

    /**
     * Returns the signals that request a graceful shutdown.
     *
     * @return list<int>
     */
    final public function getSubscribedSignals(): array
    {
        return [SIGTERM, SIGINT];
    }

    /**
     * Requests shutdown after the current item finishes.
     *
     * Returning false lets execution continue until the loop observes the flag.
     */
    final public function handleSignal(int $signal, int|false $previousExitCode = 0): int|false
    {
        $this->shouldStop = true;

        return false;
    }

    /**
     * Returns the worker name used in console output.
     */
    abstract protected function getWorkerName(): string;

    /**
     * Returns identifiers of items currently eligible for processing.
     *
     * Eligibility must be checked again when claiming an item if workers compete.
     *
     * @return list<TId>
     */
    abstract protected function fetchIds(int $limit): array;

    /**
     * Processes one item identified by the supplied identifier.
     *
     * @param TId $id
     */
    abstract protected function processId(int|string $id): void;

    /**
     * Waits between polls while checking for shutdown at least once per second.
     */
    private function wait(int $seconds): void
    {
        for ($elapsed = 0; $elapsed < $seconds; ++$elapsed) {
            if ($this->shouldStop) {
                return;
            }

            sleep(1);
        }
    }
}
