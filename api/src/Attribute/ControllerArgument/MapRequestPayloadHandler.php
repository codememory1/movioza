<?php

declare(strict_types=1);

namespace Movioza\Attribute\ControllerArgument;

use Movioza\Exception\BadRequestException;
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
            throw BadRequestException::missingRequiredFields($e->getMissingConstructorArguments(), $this->nameConverter, $e);
        } catch (ExtraAttributesException $e) {
            throw BadRequestException::extraFields($e->getExtraAttributes(), $this->nameConverter, $e);
        } catch (NotNormalizableValueException $e) {
            throw BadRequestException::invalidFieldValue($e->getPath(), $this->nameConverter, $e);
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
}
