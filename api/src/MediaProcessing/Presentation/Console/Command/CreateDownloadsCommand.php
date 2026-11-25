<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Console\Command;

use Movioza\MediaProcessing\Application\Command\CreateDownload\CreateDownloadCommand;
use Movioza\MediaProcessing\Application\Identity\DownloadIDGeneratorInterface;
use Movioza\MediaProcessing\Application\Reader\MediaSourceReaderInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;
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
    name: 'movioza:downloads:create',
    description: 'Create pending downloads from available download sources'
)]
final class CreateDownloadsCommand extends AbstractWorkerCommand
{
    public function __construct(
        private readonly DownloadIDGeneratorInterface $downloadIDGenerator,
        private readonly MediaSourceReaderInterface $mediaSourceReader,
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
        return 'Create pending downloads';
    }

    protected function fetchIds(int $limit): array
    {
        return $this->mediaSourceReader->findDownloadSourceIdsWithoutDownloads($limit);
    }

    protected function processId(int|string $id): void
    {
        $this->bus->dispatch(new CreateDownloadCommand(
            new DownloadID($this->downloadIDGenerator->next()),
            new DownloadSourceID($id)
        ));
    }
}
