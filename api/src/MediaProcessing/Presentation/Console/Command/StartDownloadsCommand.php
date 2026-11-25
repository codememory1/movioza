<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Console\Command;

use Movioza\MediaProcessing\Application\Command\StartDownload\StartDownloadCommand;
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
    name: 'movioza:downloads:start',
    description: 'Submit pending downloads to the downloader',
)]
final class StartDownloadsCommand extends AbstractWorkerCommand
{
    public function __construct(
        private readonly CommandBusInterface $bus,
        private readonly DownloadReaderInterface $downloadReader,
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
        return 'Start pending downloads';
    }

    protected function fetchIds(int $limit): array
    {
        return $this->downloadReader->findPendingIds($limit);
    }

    protected function processId(int|string $id): void
    {
        $this->bus->dispatch(new StartDownloadCommand(
            new DownloadID($id),
        ));
    }
}
