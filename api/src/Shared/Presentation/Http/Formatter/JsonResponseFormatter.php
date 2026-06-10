<?php

declare(strict_types = 1);

namespace Movioza\Shared\Presentation\Http\Formatter;

use Movioza\Shared\Presentation\Http\Response\JsonResponseBuilder;
use Movioza\Shared\Presentation\Http\Response\ResponseBuilderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @implements ResponseFormatterInterface<JsonResponseBuilder>
 */
readonly class JsonResponseFormatter implements ResponseFormatterInterface
{
    public function __construct(
        private NormalizerInterface $normalizer
    ) {
    }

    public function supports(ResponseBuilderInterface $builder): bool
    {
        return $builder instanceof JsonResponseBuilder;
    }

    /**
     * @throws ExceptionInterface
     */
    public function format(ResponseBuilderInterface $builder): Response
    {
        assert($builder instanceof JsonResponseBuilder);

        $data = [];

        if ($builder->getErrorMessage() !== null) {
            $data['success'] = false;
            $data['error']['message'] = $builder->getErrorMessage();
        } else {
            $data['success'] = true;
        }

        if ($builder->getResource() !== null) {
            $data['data'] = $this->normalizer->normalize(
                $builder->getResource(),
                'array',
                array_merge(
                    ['groups' => $builder->getGroups()],
                    $builder->getSerializerContext()
                )
            );
        }

        if ($builder->getMeta() !== []) {
            $data['meta'] = $builder->getMeta();
        }

        return new JsonResponse($data, $builder->getHttpCode(), $builder->getHeaders());
    }
}
