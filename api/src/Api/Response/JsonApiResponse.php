<?php

declare(strict_types=1);

namespace Movioza\Api\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JsonApiResponse implements ApiResponseInterface
{
    private array $response = [];
    private int $httpCode = 200;
    private array $headers = [];

    public function __construct(
        private readonly NormalizerInterface $normalizer
    ) {
    }

    public function success(int $httpCode, array $headers = []): static
    {
        $this->response['success'] = true;

        return $this;
    }

    public function error(string $message, int $httpCode, array $headers = []): static
    {
        $this->response['success'] = false;
        $this->response['error'] = [
            'message' => $message,
        ];

        return $this;
    }

    /**
     * @throws ExceptionInterface
     */
    public function resource(object|array $resource, array $context = []): static
    {
        $this->response['data'] = $this->normalizer->normalize($resource, 'array', $context);

        return $this;
    }

    public function withPagination(int $totalPages, int $currentPage): static
    {
        return $this->addMeta('pagination', [
            'page' => $currentPage,
            'total_pages' => $totalPages,
        ]);
    }

    public function addMeta(string $key, mixed $value): static
    {
        if (!isset($this->response['meta'])) {
            $this->response['meta'] = [];
        }

        $this->response['meta'][$key] = $value;

        return $this;
    }

    public function response(): Response
    {
        return new JsonResponse($this->response, $this->httpCode, $this->headers);
    }
}
