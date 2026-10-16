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
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @implements AttributeHandlerInterface<MapRequestPayload>
 */
final readonly class MapRequestPayloadHandler implements AttributeHandlerInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private CamelCaseToSnakeCaseNameConverter $nameConverter
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function handle(AttributeInterface $attribute, Request $request, ArgumentMetadata $argument): mixed
    {
        $dto = $this->deserialize($request, $argument);

        $this->validate($dto);

        return $dto;
    }

    /**
     * @throws ExceptionInterface
     */
    private function deserialize(Request $request, ArgumentMetadata $argument): object
    {
        try {
            return $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json', [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
            ]);
        } catch (NotEncodableValueException $e) {
            throw InvalidRequestPayloadException::invalidJson($e);
        } catch (MissingConstructorArgumentsException $e) {
            $fields = array_map(
                fn (string $field): string =>
                $this->nameConverter->normalize($field),
                $e->getMissingConstructorArguments()
            );

            throw InvalidRequestPayloadException::missingRequiredFields($fields, $e);
        } catch (ExtraAttributesException $e) {
            throw InvalidRequestPayloadException::unknownFields($e->getExtraAttributes(), $e);
        } catch (NotNormalizableValueException $e) {
            $path = null;

            if ($e->getPath() !== null) {
                $path = $this->nameConverter->normalize($e->getPath());
            }

            throw InvalidRequestPayloadException::invalidField($path, $e);
        }
    }

    private function validate(object $dto): void
    {
        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) {
            throw RequestValidationException::fromViolations($errors);
        }
    }
}
