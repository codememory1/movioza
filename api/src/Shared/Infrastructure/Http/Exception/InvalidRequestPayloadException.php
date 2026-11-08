<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Exception;

use Movioza\Shared\Infrastructure\Http\Error\ErrorExtensionsProviderInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Throwable;

class InvalidRequestPayloadException extends BadRequestHttpException implements ErrorExtensionsProviderInterface
{
    /**
     * @param array<string, mixed> $extensions
     */
    protected function __construct(
        private readonly array $extensions = [],
        ?Throwable $previous = null
    ) {
        parent::__construct('Invalid request payload.', previous: $previous);
    }

    public static function invalidJson(?Throwable $previous = null): self
    {
        return new InvalidJsonPayloadException(previous: $previous);
    }

    /**
     * @param list<string> $fields
     */
    public static function missingRequiredFields(array $fields, ?Throwable $previous = null): self
    {
        return new MissingRequiredFieldsException(['fields' => array_values($fields)], $previous);
    }

    /**
     * @param list<string> $fields
     */
    public static function unknownFields(array $fields, ?Throwable $previous = null): self
    {
        return new UnknownFieldsException(['fields' => array_values($fields)], $previous);
    }

    public static function invalidFieldType(?string $field, ?Throwable $previous = null): self
    {
        return new InvalidFieldTypeException(['field' => $field], $previous);
    }

    public static function invalidFieldValue(?string $field, ?Throwable $previous = null): self
    {
        return new InvalidFieldValueException(['field' => $field], $previous);
    }

    public static function invalidField(?string $field, ?Throwable $previous = null): self
    {
        return new InvalidFieldException(['field' => $field], $previous);
    }

    /**
     * @return array<string, mixed>
     */
    public function getErrorExtensions(): array
    {
        return $this->extensions;
    }
}
