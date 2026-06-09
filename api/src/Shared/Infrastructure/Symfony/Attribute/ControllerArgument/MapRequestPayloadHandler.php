<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\Symfony\Attribute\ControllerArgument;

use Movioza\Shared\Application\Exception\BadRequestException;
use Movioza\Shared\Attribute\ControllerArgument\AttributeInterface;
use Movioza\Shared\Attribute\ControllerArgument\MapRequestPayload;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
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
readonly class MapRequestPayloadHandler implements AttributeHandlerInterface
{
    public function __construct(
        #[Autowire(service: 'movioza.serializer.request_payload')]
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private CamelCaseToSnakeCaseNameConverter $nameConverter
    ) {
    }

    public static function getSupportedAttribute(): string
    {
        return MapRequestPayload::class;
    }

    public function handle(AttributeInterface $attribute, Request $request, ArgumentMetadata $argument): object
    {
        $dto = $this->deserialize($request, $argument);

        $this->validate($dto);

        return $dto;
    }

    private function deserialize(Request $request, ArgumentMetadata $argument): object
    {
        try {
            return $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json', [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
            ]);
        } catch (NotEncodableValueException $e) {
            throw BadRequestException::invalidRequestPayload($e);
        } catch (MissingConstructorArgumentsException $e) {
            throw BadRequestException::missingRequiredFields($this->normalizeFields($e->getMissingConstructorArguments()), $e);
        } catch (ExtraAttributesException $e) {
            throw BadRequestException::extraFields($this->normalizeFields($e->getExtraAttributes()), $e);
        } catch (NotNormalizableValueException $e) {
            throw BadRequestException::invalidFieldValue($this->nameConverter->normalize($e->getPath()), $e);
        } catch (ExceptionInterface $e) {
            throw new BadRequestException('Invalid request.', previous: $e);
        }
    }

    private function validate(object $dto): void
    {
        foreach ($this->validator->validate($dto) as $constraintViolation) {
            throw new BadRequestException($constraintViolation->getMessage());
        }
    }

    private function normalizeFields(array $fields): array
    {
        return array_map(fn (string $field): string => $this->nameConverter->normalize($field), $fields);
    }
}
