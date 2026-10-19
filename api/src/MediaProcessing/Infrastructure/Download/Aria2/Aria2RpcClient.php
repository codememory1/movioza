<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Download\Aria2;

use Random\RandomException;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use UnexpectedValueException;

final readonly class Aria2RpcClient
{
    public function __construct(
        private HttpClientInterface $client,
        private string $endpoint,
        private string $secret
    ) {
    }

    /**
     * @param list<string> $uris
     * @param array<string, mixed> $options
     *
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function addUri(array $uris, array $options = []): string
    {
        return $this->request('aria2.addUri', [
            $uris,
            (object) $options,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function addTorrent(string $torrent, array $options = []): string
    {
        $content = @file_get_contents($torrent);

        if ($content === false) {
            throw new RuntimeException(
                sprintf('Cannot read torrent file "%s".', $torrent),
            );
        }

        return $this->request('aria2.addTorrent', [
            base64_encode($content),
            [],
            (object) $options,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function tellStatus(string $gid): Aria2TaskStatus
    {
        $result = $this->request('aria2.tellStatus', [$gid]);

        if (!is_array($result)) {
            throw new UnexpectedValueException(
                'Expected an object from aria2.tellStatus.',
            );
        }

        return Aria2TaskStatus::fromArray($result);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function remove(string $gid): void
    {
        $this->request('aria2.remove', [$gid]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws RandomException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function request(string $method, array $params): mixed
    {
        if (trim($this->secret) !== '') {
            array_unshift($params, sprintf('token:%s', $this->secret));
        }

        $requestId = bin2hex(random_bytes(8));

        $response = $this->client->request('POST', $this->endpoint, [
            'json' => [
                'jsonrpc' => '2.0',
                'id' => $requestId,
                'method' => $method,
                'params' => $params,
            ],
            'max_duration' => 30,
        ])->toArray();

        $responseId = $response['id'] ?? null;

        if ($responseId !== $requestId) {
            throw new UnexpectedValueException('Unexpected aria2 response ID.');
        }

        if (isset($response['error'])) {
            $errorMessage = $response['error']['message'] ?? 'Unknown error';
            $errorCode = (int) ($response['error']['code'] ?? 0);

            throw new RuntimeException(sprintf('aria2 RPC %s failed: %s', $method, $errorMessage), $errorCode);
        }

        if (!array_key_exists('result', $response)) {
            throw new UnexpectedValueException('Missing result in aria2 response.');
        }

        return $response['result'];
    }
}
