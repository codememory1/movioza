<?php

declare(strict_types=1);

namespace Movioza\Command;

use Override;
use Psr\Log\LoggerInterface;

use const SIGINT;
use const SIGQUIT;
use const SIGTERM;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\SignalableCommandInterface;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractWorkerCommand extends Command implements SignalableCommandInterface
{
    private int $startedAt;

    protected bool $shouldStop = false;

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            'memory-limit',
            null,
            InputOption::VALUE_REQUIRED,
            'Maximum memory usage in megabytes before the worker gracefully stops.',
            100
        );
        $this->addOption(
            'interval',
            null,
            InputOption::VALUE_REQUIRED,
            'Time to wait before starting the next iteration (milliseconds).',
            100
        );
        $this->addOption(
            'max-runtime',
            null,
            InputOption::VALUE_REQUIRED,
            'Stop the worker after the specified number of seconds.',
            3600
        );
    }

    #[Override]
    public function getSubscribedSignals(): array
    {
        return [SIGTERM, SIGINT, SIGQUIT];
    }

    #[Override]
    public function handleSignal(int $signal, false|int $previousExitCode = 0): int|false
    {
        $this->shouldStop = true;

        return false;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->startedAt = time();

        $this->logger->info(sprintf('Worker "%s" started.', static::class));

        while (!$this->shouldStop) {
            // If the worker works longer than it should, then we terminate the work
            if ($this->hasReachedTimeLimit($input)) {
                $this->logger->warning(sprintf('Worker "%s" stopped by time limit.', static::class));

                break;
            }

            // If the worker uses more memory than it should, we terminate the work.
            if ($this->hasReachedMemoryLimit($input)) {
                $this->logger->warning(sprintf('Worker "%s" stopped by memory limit.', static::class), [
                    'memory' => memory_get_usage(true),
                ]);

                break;
            }

            $this->process($input, $output);

            usleep($this->getInterval($input) * 1000);
        }

        $this->logger->info(sprintf('Worker "%s" stopped.', static::class));

        return self::SUCCESS;
    }

    abstract protected function process(InputInterface $input, OutputInterface $output): void;

    private function getMemoryLimit(InputInterface $input): int
    {
        $option = filter_var($input->getOption('memory-limit'), FILTER_VALIDATE_INT);

        if ($option === false) {
            throw new InvalidOptionException('The "--memory-limit" option must be an integer.');
        }

        return $option;
    }

    private function getInterval(InputInterface $input): int
    {
        $option = filter_var($input->getOption('interval'), FILTER_VALIDATE_INT);

        if ($option === false) {
            throw new InvalidOptionException('The "--interval" option must be an integer.');
        }

        return $option;
    }

    private function getMaxRuntime(InputInterface $input): int
    {
        $option = filter_var($input->getOption('max-runtime'), FILTER_VALIDATE_INT);

        if ($option === false) {
            throw new InvalidOptionException('The "--max-runtime" option must be an integer.');
        }

        return $option;
    }

    private function hasReachedMemoryLimit(InputInterface $input): bool
    {
        return memory_get_usage(true) >= ($this->getMemoryLimit($input) * 1024 * 1024);
    }

    private function hasReachedTimeLimit(InputInterface $input): bool
    {
        return (time() - $this->startedAt) >= $this->getMaxRuntime($input);
    }
}
