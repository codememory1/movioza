<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Pagination;

use InvalidArgumentException;
use JsonException;
use Movioza\Shared\Application\Exception\InvalidCursorPaginationException;
use Movioza\Shared\Application\Pagination\CursorCodecInterface;
use Movioza\Shared\Application\Pagination\CursorPositionInterface;

final readonly class HmacCursorCodec implements CursorCodecInterface
{
    public function __construct(
        private string $secret
    ) {
    }

    /**
     * @throws JsonException
     */
    public function encode(CursorPositionInterface $position): string
    {
        $payloadJson = json_encode([
            'scope' => $position->scope(),
            'position' => $position->toArray(),
        ], JSON_THROW_ON_ERROR);

        return sprintf(
            '%s.%s',
            $this->encodeBase64Url($payloadJson),
            $this->encodeBase64Url($this->createSignature($payloadJson))
        );
    }

    public function decode(string $cursor, string $position): CursorPositionInterface
    {
        if (!class_exists($position) || !is_subclass_of($position, CursorPositionInterface::class)) {
            throw new InvalidArgumentException('Expected a concrete class implementing CursorPositionInterface.');
        }

        $parts = explode('.', $cursor, 2);

        if (count($parts) !== 2) {
            throw new InvalidCursorPaginationException();
        }

        [$encodedPayload, $encodedSignature] = $parts;
        $payloadJson = $this->decodeBase64Url($encodedPayload);
        $providedSignature = $this->decodeBase64Url($encodedSignature);
        $expectedSignature = $this->createSignature($payloadJson);

        if (!hash_equals($expectedSignature, $providedSignature)) {
            throw new InvalidCursorPaginationException();
        }

        try {
            $payload = json_decode($payloadJson, true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new InvalidCursorPaginationException();
        }

        if (!is_array($payload) || $payload['scope'] !== $position::scope()) {
            throw new InvalidCursorPaginationException();
        }

        return $position::fromArray($payload['position']);
    }

    private function createSignature(string $payloadJson): string
    {
        return hash_hmac('sha256', $payloadJson, $this->secret, true);
    }

    private function encodeBase64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function decodeBase64Url(string $value): string
    {
        if (preg_match('/\A[A-Za-z0-9_-]+\z/', $value) !== 1) {
            throw new InvalidCursorPaginationException();
        }

        $decoded = base64_decode(strtr($value, '-_', '+/'), true);

        if ($decoded === false || $this->encodeBase64Url($decoded) !== $value) {
            throw new InvalidCursorPaginationException();
        }

        return $decoded;
    }
}
