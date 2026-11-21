<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Presentation\Console\Command;

use Movioza\MediaProcessing\Application\Command\ResolveMediaSource\ResolveMediaSourceCommand;
use Movioza\MediaProcessing\Application\Reader\MediaSourceReaderInterface;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
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
    name: 'movioza:media-sources:resolve',
    description: 'Resolve media sources',
)]
final class ResolveMediaSourcesCommand extends AbstractWorkerCommand
{
    public function __construct(
        private readonly MediaSourceReaderInterface $mediaSourceReader,
        private readonly CommandBusInterface $bus,
        LoggerInterface $logger,
        #[Autowire(service: 'services_resetter')]
        ResetInterface $servicesResetter
    ) {
        parent::__construct($logger, $servicesResetter);
    }

    /**
     * Starts the media source resolver with the supplied worker options.
     */
    public function __invoke(
        SymfonyStyle $io,
        #[MapInput] WorkerOptions $options
    ): int {
        return $this->run($io, $options);
    }

    /**
     * Returns the name displayed during worker startup and shutdown.
     */
    protected function getWorkerName(): string
    {
        return 'Media source resolver';
    }

    /**
     * Returns pending media source identifiers for the next batch.
     *
     * @return list<int>
     */
    protected function fetchIds(int $limit): array
    {
        return $this->mediaSourceReader->findPendingIDs($limit);
    }

    /**
     * Dispatches synchronous resolution of one media source.
     */
    protected function processId(int|string $id): void
    {
        $this->bus->dispatch(new ResolveMediaSourceCommand(
            new MediaSourceID($id)
        ));
    }
}
