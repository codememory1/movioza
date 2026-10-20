<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\CreateMediaSource;

use DateTimeImmutable;
use Movioza\MediaProcessing\Application\Exception\ReferencedMediaNotFoundException;
use Movioza\MediaProcessing\Application\Media\MediaExistenceCheckerInterface;
use Movioza\MediaProcessing\Domain\Entity\MediaSource;
use Movioza\MediaProcessing\Domain\Repository\MediaSourceRepositoryInterface;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;

final readonly class CreateMediaSourceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private MediaSourceRepositoryInterface $mediaSourceRepository,
        private MediaExistenceCheckerInterface $mediaExistenceChecker,
        private TransactionManagerInterface $tm
    ) {
    }

    public function __invoke(CreateMediaSourceCommand $command): void
    {
        if (!$this->mediaExistenceChecker->exists($command->mediaID->getValue())) {
            throw new ReferencedMediaNotFoundException($command->mediaID->getValue());
        }

        $mediaSource = new MediaSource(
            $command->ID,
            $command->mediaID,
            $command->pageUrl,
            new DateTimeImmutable()
        );

        $this->tm->transactional(function() use ($mediaSource) {
            $this->mediaSourceRepository->save($mediaSource);
        });
    }
}
