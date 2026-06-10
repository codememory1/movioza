<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Aria2;

use Movioza\Shared\Domain\Downloader\DownloaderInterface;
use Movioza\Shared\Domain\Downloader\Enum\DownloadStatus;
use Movioza\Shared\Domain\Downloader\Exception\DownloaderException;
use Movioza\Shared\Domain\Downloader\ValueObject\DownloadMetrics;
use Movioza\Shared\Domain\ValueObject\MagnetLink;
use Movioza\Shared\Domain\ValueObject\TorrentFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Aria2Downloader implements DownloaderInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $host,
        private int $port,
        private string $secret
    ) {
    }

    public function downloadTorrent(TorrentFile $file): string
    {
        $response = $this->request('aria2.addTorrent', [
            base64_encode(file_get_contents($file->value)),
        ]);

        return $response['result'];
    }

    public function downloadMagnet(MagnetLink $magnetLink): string
    {
        $response = $this->request('aria2.addUri', [[$magnetLink->value]]);

        return $response['result'];
    }

    public function getMetrics(string $gid): DownloadMetrics
    {
        $response = $this->request('aria2.tellStatus', [$gid]);
        $result = $response['result'];
        $status = match ($result['status']) {
            'active' => DownloadStatus::ACTIVE,
            'waiting' => DownloadStatus::WAITING,
            'paused' => DownloadStatus::PAUSED,
            'error' => DownloadStatus::ERROR,
            'removed' => DownloadStatus::CANCELED,
            'complete' => DownloadStatus::COMPLETED,
        };

        return new DownloadMetrics(
            $gid,
            $status,
            (int) $result['totalLength'],
            (int) $result['completedLength'],
            (int) $result['downloadSpeed'],
            $result['followedBy'] ?? null
        );
    }

    public function getActiveGIDs(): array
    {
        $response = $this->request('aria2.tellActive');
        $result = [];

        foreach ($response['result'] as $item) {
            $result[] = $item['gid'];
        }

        return $result;
    }

    public function cancel(string $gid): void
    {
        $this->request('aria2.remove', [$gid]);
    }

    private function request(string $method, array $params = []): array
    {
        try {
            return $this->client->request(Request::METHOD_POST, $this->resolveHost(), [
                'json' => [
                    'jsonrpc' => '2.0',
                    'id' => Uuid::v7()->toString(),
                    'method' => $method,
                    'params' => [
                        "token:$this->secret",
                        ...$params,
                    ],
                ],
            ])->toArray();
        } catch (TransportExceptionInterface | HttpExceptionInterface | DecodingExceptionInterface $e) {
            throw new DownloaderException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function resolveHost(): string
    {
        return sprintf('%s:%s/jsonrpc', rtrim($this->host, '/'), $this->port);
    }
}
