<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Console\Command;

use Movioza\MediaProcessing\Application\Command\RecoverDownloadStart\RecoverDownloadStartCommand;
use Movioza\MediaProcessing\Application\Reader\DownloadReaderInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Presentation\Console\AbstractWorkerCommand;
use Movioza\Shared\Presentation\Console\Input\WorkerOptions;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\MapInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\ResetInterface;

#[AsCommand(
    name: 'movioza:downloads:recover-starts',
    description: 'Recover interrupted download starts with expired leases',
)]
final class RecoverDownloadStartsCommand extends AbstractWorkerCommand
{
    private ?int $lastDownloadId = null;

    public function __construct(
        private readonly DownloadReaderInterface $downloadReader,
        private readonly CommandBusInterface $bus,
        private readonly ClockInterface $clock,
        LoggerInterface $logger,
        #[Autowire(service: 'services_resetter')]
        ResetInterface $servicesResetter
    ) {
        parent::__construct($logger, $servicesResetter);
    }

    public function __invoke(
        SymfonyStyle $io,
        #[MapInput] WorkerOptions $options
    ): int {
        return $this->run($io, $options);
    }

    protected function getWorkerName(): string
    {
        return 'Recover download starts';
    }

    protected function fetchIds(int $limit): array
    {
        $ids = $this->downloadReader->findIdsForStartRecovery($limit, $this->clock->now(), $this->lastDownloadId);

        $this->lastDownloadId = $ids === [] ? null : array_last($ids);

        return $ids;
    }

    protected function processId(int|string $id): void
    {
        $this->bus->dispatch(new RecoverDownloadStartCommand(
            new DownloadID($id)
        ));
    }
}
