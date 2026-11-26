<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Console\Command;

use Movioza\MediaProcessing\Application\Command\SyncDownload\SyncDownloadCommand;
use Movioza\MediaProcessing\Application\Reader\DownloadReaderInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\Shared\Application\Bus\Command\CommandBusInterface;
use Movioza\Shared\Presentation\Console\AbstractWorkerCommand;
use Movioza\Shared\Presentation\Console\Input\WorkerOptions;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\MapInput;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Service\ResetInterface;

#[AsCommand(
    name: 'movioza:downloads:sync',
    description: 'Synchronize download statuses, progress, and files with the downloader',
)]
final class SyncDownloadsCommand extends AbstractWorkerCommand
{
    private ?int $lastDownloadId = null;

    public function __construct(
        private readonly DownloadReaderInterface $downloadReader,
        private readonly CommandBusInterface $bus,
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
        return 'Synchronize downloads';
    }

    protected function fetchIds(int $limit): array
    {
        $ids = $this->downloadReader->findIdsForSync($limit, $this->lastDownloadId);

        $this->lastDownloadId = $ids === [] ? null : array_last($ids);

        return $ids;
    }

    protected function processId(int|string $id): void
    {
        $this->bus->dispatch(new SyncDownloadCommand(
            new DownloadID($id)
        ));
    }
}
