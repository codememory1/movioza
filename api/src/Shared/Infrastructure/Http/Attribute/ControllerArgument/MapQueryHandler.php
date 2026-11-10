<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument;

use Movioza\Shared\Infrastructure\Http\Exception\InvalidRequestPayloadException;
use Movioza\Shared\Infrastructure\Http\Exception\RequestValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\ExtraAttributesException;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @implements AttributeHandlerInterface<MapQuery>
 */
final readonly class MapQueryHandler implements AttributeHandlerInterface
{
    public function __construct(
        private DenormalizerInterface $denormalizer,
        private ValidatorInterface $validator,
        private CamelCaseToSnakeCaseNameConverter $nameConverter
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function handle(AttributeInterface $attribute, Request $request, ArgumentMetadata $argument): mixed
    {
        $dto = $this->denormalize($request, $argument);

        $this->validate($dto);

        return $dto;
    }

    /**
     * @throws ExceptionInterface
     */
    private function denormalize(Request $request, ArgumentMetadata $argument): object
    {
        try {
            return $this->denormalizer->denormalize($request->query->all(), $argument->getType(), 'csv', [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
                'filter_bool' => true,
            ]);
        } catch (MissingConstructorArgumentsException $e) {
            throw InvalidRequestPayloadException::missingRequiredFields(
                $this->normalizeFields($e->getMissingConstructorArguments()),
                $e
            );
        } catch (ExtraAttributesException $e) {
            throw InvalidRequestPayloadException::unknownFields(
                $this->normalizeFields($e->getExtraAttributes()),
                $e
            );
        } catch (NotNormalizableValueException $e) {
            $path = null;

            if ($e->getPath() !== null) {
                $path = $this->nameConverter->normalize($e->getPath());
            }

            throw InvalidRequestPayloadException::invalidField($path, $e);
        }
    }

    /**
     * @param list<string> $fields
     *
     * @return list<string>
     */
    private function normalizeFields(array $fields): array
    {
        return array_map(
            fn (string $field): string => $this->nameConverter->normalize($field),
            $fields
        );
    }

    private function validate(object $dto): void
    {
        $violations = $this->validator->validate($dto);

        if ($violations->count() > 0) {
            throw RequestValidationException::fromViolations($violations);
        }
    }
}
