<?php

declare(strict_types=1);

namespace Movioza\Service\Aria2;

use Movioza\Service\Aria2\Response\AddTorrentResponse;
use Movioza\Service\Aria2\Response\RemoveResponse;
use Movioza\Service\Aria2\Response\TellActiveResponse;
use Movioza\Service\Aria2\Response\TellStatusResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class Aria2Client implements Aria2ClientInterface
{
    public function __construct(
        private HttpClientInterface $client,
        private string $host,
        private int $port,
        private string $secret
    ) {
    }

    public function tellStatus(string $gid): TellStatusResponse
    {
        $response = $this->request('aria2.tellStatus', [$gid])->toArray();

        return new TellStatusResponse($response);
    }

    public function tellActive(): TellActiveResponse
    {
        $response = $this->request('aria2.tellActive')->toArray();

        return new TellActiveResponse($response);
    }

    public function addTorrent(string $path): AddTorrentResponse
    {
        $response = $this->request('aria2.addTorrent', [
            base64_encode(file_get_contents($path)),
        ])->toArray();

        return new AddTorrentResponse($response);
    }

    public function addLinks(string ...$links): AddTorrentResponse
    {
        $response = $this->request('aria2.addUri', [$links])->toArray();

        return new AddTorrentResponse($response);
    }

    public function remove(string $GID): RemoveResponse
    {
        $response = $this->request('aria2.remove', [$GID])->toArray();

        return new RemoveResponse($response);
    }

    public function request(string $method, array $params = []): ResponseInterface
    {
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
        ]);
    }

    private function resolveHost(): string
    {
        return sprintf('%s:%s/jsonrpc', rtrim($this->host, '/'), $this->port);
    }
}
