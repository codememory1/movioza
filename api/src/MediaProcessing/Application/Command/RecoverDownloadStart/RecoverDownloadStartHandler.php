<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\RecoverDownloadStart;

use Movioza\MediaProcessing\Application\Exception\DownloadNotFoundException;
use Movioza\MediaProcessing\Domain\Repository\DownloadRepositoryInterface;
use Movioza\MediaProcessing\Domain\StateMachine\DownloadStateMachineInterface;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;

final readonly class RecoverDownloadStartHandler implements CommandHandlerInterface
{
    public function __construct(
        private TransactionManagerInterface $tm,
        private DownloadRepositoryInterface $downloadRepository,
        private ClockInterface $clock,
        private DownloadStateMachineInterface $stateMachine
    ) {
    }

    public function __invoke(RecoverDownloadStartCommand $command): void
    {
        $this->tm->transactional(function () use ($command): void {
            $download = $this->downloadRepository->findByIdForUpdate($command->downloadId);

            if ($download === null) {
                throw new DownloadNotFoundException($command->downloadId);
            }

            $now = $this->clock->now();

            if (!$download->canRecoverStart($now)) {
                return;
            }

            $download->applyRequeue($now, $this->stateMachine);

            $this->downloadRepository->save($download);
        });
    }
}
