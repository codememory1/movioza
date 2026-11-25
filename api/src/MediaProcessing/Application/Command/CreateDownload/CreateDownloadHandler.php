<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\CreateDownload;

use Movioza\MediaProcessing\Application\Exception\DownloadSourceNotFoundException;
use Movioza\MediaProcessing\Domain\Entity\Download;
use Movioza\MediaProcessing\Domain\Repository\DownloadRepositoryInterface;
use Movioza\MediaProcessing\Domain\Repository\MediaSourceRepositoryInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DestinationDirectory;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadID;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;

final readonly class CreateDownloadHandler implements CommandHandlerInterface
{
    public function __construct(
        private TransactionManagerInterface $tm,
        private MediaSourceRepositoryInterface $mediaSourceRepository,
        private DownloadRepositoryInterface $downloadRepository,
        private ClockInterface $clock,
        private string $downloadsDirectory
    ) {
    }

    public function __invoke(CreateDownloadCommand $command): void
    {
        $this->tm->transactional(function () use ($command) {
            $mediaSource = $this->mediaSourceRepository->findByDownloadSourceId($command->downloadSourceId);

            if ($mediaSource === null) {
                throw new DownloadSourceNotFoundException($command->downloadSourceId);
            }

            $downloadSource = $mediaSource->findDownloadSourceById($command->downloadSourceId);

            if ($downloadSource === null) {
                throw new DownloadSourceNotFoundException($command->downloadSourceId);
            }

            $this->downloadRepository->save(new Download(
                $command->downloadId,
                $command->downloadSourceId,
                new DestinationDirectory($this->resolveDestinationDirectory(
                    $command->downloadId
                )),
                $this->clock->now()
            ));
        });
    }

    private function resolveDestinationDirectory(DownloadID $downloadId): string
    {
        return sprintf(
            '%s/%s',
            rtrim($this->downloadsDirectory, '/'),
            $downloadId->getValue()
        );
    }
}
