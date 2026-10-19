<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Download\Aria2;

use Movioza\MediaProcessing\Application\Download\DownloaderInterface;
use Movioza\MediaProcessing\Application\Download\DownloadID;
use Movioza\MediaProcessing\Application\Download\DownloadRequest;
use Movioza\MediaProcessing\Application\Download\DownloadStatus;
use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;
use Movioza\MediaProcessing\Domain\Enum\DownloadState;
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
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RandomException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function start(DownloadRequest $request): DownloadID
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
    public function getStatus(DownloadID $id): DownloadStatus
    {
        $result = $this->resolveCurrentStatus($id);

        return new DownloadStatus(
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
    public function cancel(DownloadID $id): void
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
    private function startTorrent(DownloadRequest $request): DownloadID
    {
        $GID = $this->rpc->addTorrent($request->source);

        return new DownloadID($GID);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function startMagnet(DownloadRequest $request): DownloadID
    {
        $GID = $this->rpc->addUri([$request->source]);

        return new DownloadID($GID);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function resolveCurrentStatus(DownloadID $downloadId): Aria2TaskStatus
    {
        $gid = $downloadId->id;
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
