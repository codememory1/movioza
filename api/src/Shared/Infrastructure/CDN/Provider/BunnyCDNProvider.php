<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\CDN\Provider;

use Movioza\Shared\Domain\CDN\CDNProviderInterface;
use Movioza\Shared\Domain\CDN\Enum\CdnFileType;

readonly class BunnyCDNProvider implements CDNProviderInterface
{
    public function __construct(
        private string $domain,
        private string $secretKey
    ) {
    }

    public function supports(CdnFileType $type): bool
    {
        return in_array($type, [
            CdnFileType::IMAGE,
            CdnFileType::VIDEO,
        ], true);
    }

    public function generate(CdnFileType $type, string $path, int $ttl): string
    {
        $expires = time() + $ttl;

        return sprintf(
            '%s/%s?token=%s&expires=%s',
            rtrim($this->domain, '/'),
            ltrim($path, '/'),
            $this->token($this->signature($path, $expires)),
            $expires
        );
    }

    private function signature(string $path, int $expires): string
    {
        return hash_hmac('sha256', $path . $expires, $this->secretKey, true);
    }

    private function token(string $signature): string
    {
        return sprintf('HS256-%s', rtrim(strtr(base64_encode($signature), '+/', '-_'), '='));
    }
}
