<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\TorrentTracker\Provider;

use Movioza\Shared\Domain\TorrentTracker\Exception\TorrentTrackerException;
use Movioza\Shared\Domain\TorrentTracker\TorrentTrackerProviderInterface;
use Movioza\Shared\Domain\ValueObject\MagnetLink;
use Movioza\Shared\Domain\ValueObject\TargetFilePath;
use Movioza\Shared\Domain\ValueObject\TorrentFile;
use Movioza\Shared\Domain\ValueObject\Url;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RutorProvider implements TorrentTrackerProviderInterface
{
    private const array DOMAINS = ['https://rutor.info'];

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
    }

    public function supports(Url $url): bool
    {
        return array_any(self::DOMAINS, static fn (string $domain): bool => str_starts_with($url->value, $domain));
    }

    public function downloadTorrentFile(Url $url, TargetFilePath $targetFilePath): ?TorrentFile
    {
        $links = $this->getDownloadLinksFromUrl($url);

        if ($links->count() < 2) {
            return null;
        }

        $downloadUrl = sprintf('https:%s', $links->eq(1)->attr('href'));

        try {
            $torrentContent = $this->client->request(Request::METHOD_GET, $downloadUrl)->getContent();
        } catch (TransportExceptionInterface | HttpExceptionInterface $e) {
            throw new TorrentTrackerException($e->getMessage(), $e->getCode(), $e);
        }

        $putResult = file_put_contents($targetFilePath->value, $torrentContent);

        return false === $putResult ? null : new TorrentFile($targetFilePath->value);
    }

    public function getMagnetLink(Url $url): ?MagnetLink
    {
        $links = $this->getDownloadLinksFromUrl($url);

        if ($links->count() < 1) {
            return null;
        }

        return new MagnetLink($links->eq(0)->attr('href'));
    }

    private function getDownloadLinksFromUrl(Url $url): Crawler
    {
        try {
            $content = $this->client->request(Request::METHOD_GET, $url->value)->getContent();
        } catch (TransportExceptionInterface | HttpExceptionInterface $e) {
            throw new TorrentTrackerException($e->getMessage(), $e->getCode(), $e);
        }

        return $this->getDownloadLinks($content);
    }

    private function getDownloadLinks(string $html): Crawler
    {
        $crawler = new Crawler($html);

        return $crawler->filter('#download a');
    }
}
