<?php

declare(strict_types=1);

namespace Movioza\Service\TorrentTracker\Provider;

use Movioza\Service\TorrentTracker\ProviderInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RutorProvider implements ProviderInterface
{
    private const array DOMAINS = [
        'https://rutor.info',
    ];

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function supports(string $url): bool
    {
        return array_any(self::DOMAINS, static fn (string $domain): bool => str_starts_with($url, $domain));
    }

    public function downloadTorrentFile(string $url, string $targetPath): bool
    {
        $links = $this->getDownloadLinksFromUrl($url);

        if ($links->count() < 2) {
            return false;
        }

        $downloadUrl = sprintf('https:%s', $links->eq(1)->attr('href'));
        $torrentContent = $this->client->request(Request::METHOD_GET, $downloadUrl)->getContent();

        return false !== file_put_contents($targetPath, $torrentContent);
    }

    public function getMagnetLink(string $url): ?string
    {
        $links = $this->getDownloadLinksFromUrl($url);

        if ($links->count() < 1) {
            return null;
        }

        return $links->eq(0)->attr('href');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getDownloadLinksFromUrl(string $url): Crawler
    {
        $content = $this->client->request(Request::METHOD_GET, $url)->getContent();

        return $this->getDownloadLinks($content);
    }

    private function getDownloadLinks(string $html): Crawler
    {
        $crawler = new Crawler($html);

        return $crawler->filter('#download a');
    }
}
