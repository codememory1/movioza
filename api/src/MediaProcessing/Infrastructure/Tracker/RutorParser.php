<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Tracker;

use Movioza\MediaProcessing\Application\Exception\TrackerParsingFailedException;
use Movioza\MediaProcessing\Application\Tracker\ParsedDownloadSource;
use Movioza\MediaProcessing\Application\Tracker\TrackerParserInterface;
use Movioza\MediaProcessing\Domain\Enum\DownloadSourceType;
use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;
use Movioza\MediaProcessing\Infrastructure\Tracker\Storage\TorrentFileStorageInterface;
use RuntimeException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class RutorParser implements TrackerParserInterface
{
    private const array SCHEMES = ['http', 'https'];

    /**
     * @param list<string> $supportedHosts
     */
    public function __construct(
        private HttpClientInterface $client,
        private TorrentFileStorageInterface $torrentFileStorage,
        private array $supportedHosts
    ) {
    }

    public function supports(PageUrl $pageUrl): bool
    {
        $parts = parse_url($pageUrl->getValue());

        if ($parts === false) {
            return false;
        }

        $scheme = strtolower($parts['scheme'] ?? '');
        $host = strtolower(rtrim($parts['host'] ?? '', '.'));

        return in_array($scheme, self::SCHEMES, true)
            && in_array($host, $this->supportedHosts, true);
    }

    /**
     * Prefers a magnet link and falls back to downloading and saving a local torrent file.
     *
     * @throws RuntimeException When the page has no supported download source.
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function parse(PageUrl $pageUrl): ParsedDownloadSource
    {
        $magnetLink = $this->getMagnetLink($pageUrl->getValue());

        if ($magnetLink !== null) {
            return new ParsedDownloadSource(DownloadSourceType::MAGNET, $magnetLink);
        }

        $torrentFile = $this->downloadTorrentFile($pageUrl->getValue());

        if ($torrentFile !== null) {
            return new ParsedDownloadSource(DownloadSourceType::TORRENT, $torrentFile);
        }

        throw new TrackerParsingFailedException('Tracker page contains no supported download source.');
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function downloadTorrentFile(string $url): ?string
    {
        $links = $this->getDownloadLinksFromUrl($url);

        if ($links->count() < 2) {
            return null;
        }

        $downloadUrl = sprintf('https:%s', $links->eq(1)->attr('href'));
        $content = $this->client->request(Request::METHOD_GET, $downloadUrl)->getContent();

        return $this->torrentFileStorage->save($content);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getMagnetLink(string $url): ?string
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

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function getDownloadLinks(string $html): Crawler
    {
        $crawler = new Crawler($html);

        return $crawler->filter('#download a');
    }
}
