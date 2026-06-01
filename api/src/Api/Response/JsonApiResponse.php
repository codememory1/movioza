<?php

declare(strict_types=1);

namespace Movioza\Api\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class JsonApiResponse implements ApiResponseInterface
{
    public $response;
    private array $data = [];
    private int $httpCode = 200;
    private array $headers = [];

    public function __construct(
        private readonly NormalizerInterface $normalizer
    ) {
    }

    public function success(int $status, array $headers = []): static
    {
        $this->data['success'] = true;

        $this->httpCode = $status;
        $this->headers = $headers;

        return $this;
    }

    public function error(string $message, int $status, array $headers = []): static
    {
        $this->data['success'] = false;
        $this->data['error'] = [
            'message' => $message,
        ];

        $this->httpCode = $status;
        $this->headers = $headers;

        return $this;
    }

    /**
     * @throws ExceptionInterface
     */
    public function resource(object|array $resource, array $groups = [], array $context = []): static
    {
        $this->data['data'] = $this->normalizer->normalize($resource, 'array', [
            'groups' => $groups,
            ...$context,
        ]);

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
            $this->data['meta'] = [];
        }

        $this->data['meta'][$key] = $value;

        return $this;
    }

    public function response(): Response
    {
        return new JsonResponse($this->data, $this->httpCode, $this->headers);
    }
}
