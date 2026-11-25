<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Download\Aria2;

use Movioza\MediaProcessing\Application\Download\DownloaderInterface;
use Movioza\MediaProcessing\Application\Download\DownloadRequest;
use Movioza\MediaProcessing\Application\Download\DownloadSnapshot;
use Movioza\MediaProcessing\Application\Download\DownloadState;
use Movioza\MediaProcessing\Domain\Enum\DownloaderKey;
use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;
use Movioza\MediaProcessing\Domain\ValueObject\ExternalDownloadID;
use Random\RandomException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use UnexpectedValueException;

readonly class Aria2Downloader implements DownloaderInterface
{
    private const string ACTIVE_STATUS = 'active';
    private const string WAITING_STATUS = 'waiting';
    private const string PAUSED_STATUS = 'paused';
    private const string ERROR_STATUS = 'error';
    private const string REMOVED_STATUS = 'removed';
    private const string COMPLETE_STATUS = 'complete';

    public function __construct(
        private Aria2RpcClient $rpc
    ) {
    }

    /**
     * @throws RandomException
     */
    public function generateId(): ExternalDownloadID
    {
        do {
            $value = bin2hex(random_bytes(8));
        } while ($value === '0000000000000000');

        return new ExternalDownloadID($value);
    }

    public function getKey(): DownloaderKey
    {
        return DownloaderKey::ARIA2;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RandomException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function start(DownloadRequest $request): ExternalDownloadID
    {
        return match ($request->sourceType) {
            DownloadSourceType::TORRENT => $this->startTorrent($request),
            DownloadSourceType::MAGNET => $this->startMagnet($request),
        };
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getStatus(ExternalDownloadID $id): DownloadSnapshot
    {
        $result = $this->resolveCurrentStatus($id);

        return new DownloadSnapshot(
            $this->mapStatus($result->status),
            $result->completedLength,
            $result->totalLength,
            $result->downloadSpeed,
            $result->errorMessage
        );
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function cancel(ExternalDownloadID $id): void
    {
        $status = $this->resolveCurrentStatus($id);

        $this->rpc->remove($status->gid);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function startTorrent(DownloadRequest $request): ExternalDownloadID
    {
        $GID = $this->rpc->addTorrent($request->source, [
            'gid' => $request->externalDownloadId->getValue(),
            'dir' => $request->destinationDirectory->getValue()
        ]);

        return new ExternalDownloadID($GID);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function startMagnet(DownloadRequest $request): ExternalDownloadID
    {
        $GID = $this->rpc->addUri([$request->source], [
            'gid' => $request->externalDownloadId->getValue(),
            'dir' => $request->destinationDirectory->getValue()
        ]);

        return new ExternalDownloadID($GID);
    }

    /**
     * Follows aria2 successor GIDs to the current download task.
     * Only a single-successor chain is supported.
     *
     * @throws UnexpectedValueException When the chain contains a cycle or branches.
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function resolveCurrentStatus(ExternalDownloadID $downloadId): Aria2TaskStatus
    {
        $gid = $downloadId->getValue();
        $visited = [];

        while (true) {
            if (isset($visited[$gid])) {
                throw new UnexpectedValueException(sprintf('Cycle detected for aria2 GID "%s".', $gid));
            }

            $visited[$gid] = true;

            $status = $this->rpc->tellStatus($gid);

            if ($status->followedBy === []) {
                return $status;
            }

            if (count($status->followedBy) !== 1) {
                throw new UnexpectedValueException('Expected exactly one download in followedBy.');
            }

            // Continue with the task spawned after metadata resolution instead of reporting metadata completion.
            $gid = $status->followedBy[0];
        }
    }

    private function mapStatus(string $status): DownloadState
    {
        return match ($status) {
            self::ACTIVE_STATUS => DownloadState::DOWNLOADING,
            self::WAITING_STATUS => DownloadState::PENDING,
            self::PAUSED_STATUS => DownloadState::PAUSED,
            self::ERROR_STATUS => DownloadState::FAILED,
            self::REMOVED_STATUS => DownloadState::CANCELED,
            self::COMPLETE_STATUS => DownloadState::COMPLETED,
            default => throw new UnexpectedValueException(sprintf('Unknown aria2 status: "%s".', $status)),
        };
    }
}
