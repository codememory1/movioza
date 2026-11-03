<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Application\Command\ResolveMediaSource;

use Movioza\MediaProcessing\Application\Exception\TrackerParsingFailedException;
use Movioza\MediaProcessing\Application\Identity\DownloadSourceIDGeneratorInterface;
use Movioza\MediaProcessing\Application\Tracker\ParsedDownloadSource;
use Movioza\MediaProcessing\Application\Tracker\TrackerParserRegistryInterface;
use Movioza\MediaProcessing\Domain\Entity\MediaSource;
use Movioza\MediaProcessing\Domain\Enum\MediaSourceTransition;
use Movioza\MediaProcessing\Domain\Repository\MediaSourceRepositoryInterface;
use Movioza\MediaProcessing\Domain\StateMachine\MediaSourceStateMachineInterface;
use Movioza\MediaProcessing\Domain\ValueObject\DownloadSourceID;
use Movioza\MediaProcessing\Domain\ValueObject\MediaSourceID;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final readonly class ResolveMediaSourceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private TransactionManagerInterface $tm,
        private MediaSourceRepositoryInterface $mediaSourceRepository,
        private MediaSourceStateMachineInterface $stateMachine,
        private TrackerParserRegistryInterface $trackerParserRegistry,
        private DownloadSourceIDGeneratorInterface $downloadSourceIDGenerator,
        private ClockInterface $clock,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Skips unavailable sources and records expected parsing failures as FAILED.
     *
     * Unexpected errors propagate to the caller.
     *
     * @throws Throwable
     */
    public function __invoke(ResolveMediaSourceCommand $command): void
    {
        $mediaSource = $this->claim($command->id);

        if ($mediaSource === null) {
            return;
        }

        // The claim is committed before any network requests are made.
        try {
            $trackerParser = $this->trackerParserRegistry->get($mediaSource->getPageUrl());
            $parsedDownloadSource = $trackerParser->parse($mediaSource->getPageUrl());
        } catch (Throwable $e) {
            $this->fail($mediaSource, $e);

            if (!$e instanceof TrackerParsingFailedException) {
                throw $e;
            }

            $this->logger->warning('Media source parsing failed.', [
                'media_source_id' => $mediaSource->getId()->getValue(),
                'exception' => $e
            ]);

            return;
        }

        $this->complete($mediaSource, $parsedDownloadSource);
    }

    /**
     * Returns the source after committing its transition to RESOLVING.
     *
     * @return MediaSource|null Null if the source is missing, locked or ineligible.
     */
    private function claim(MediaSourceID $id): ?MediaSource
    {
        return $this->tm->transactional(function () use ($id) {
            $mediaSource = $this->mediaSourceRepository->findByIDForUpdateSkipLocked($id);

            if ($mediaSource === null) {
                return null;
            }

            // Another worker may have claimed the source after its ID was read.
            if (!$this->stateMachine->can($mediaSource, MediaSourceTransition::START_RESOLVING)) {
                return null;
            }

            $mediaSource->applyStartResolving($this->clock->now(), $this->stateMachine);

            $this->mediaSourceRepository->save($mediaSource);

            return $mediaSource;
        });
    }

    /**
     * Persists the download source and the RESOLVED status atomically.
     */
    private function complete(MediaSource $mediaSource, ParsedDownloadSource $parsedDownloadSource): void
    {
        $this->tm->transactional(function () use ($mediaSource, $parsedDownloadSource): void {
            $resolvedAt = $this->clock->now();

            $mediaSource->addDownloadSource(
                new DownloadSourceID($this->downloadSourceIDGenerator->next()),
                $parsedDownloadSource->type,
                $parsedDownloadSource->source,
                $resolvedAt
            );

            $mediaSource->applyResolve($resolvedAt, $this->stateMachine);

            $this->mediaSourceRepository->save($mediaSource);
        });
    }

    /**
     * Persists the FAILED status and its reason before emitting a warning.
     */
    private function fail(MediaSource $mediaSource, Throwable $exception): void {
        $this->tm->transactional(function () use ($mediaSource, $exception): void {
            $mediaSource->applyFail(
                $exception->getMessage(),
                $this->clock->now(),
                $this->stateMachine
            );

            $this->mediaSourceRepository->save($mediaSource);
        });
    }
}
