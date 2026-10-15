<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Exception;

use Throwable;

final class InvalidRequestPayloadException extends HttpException
{
    public function __construct(
        string $errorCode,
        string $message,
        mixed $detail = null,
        array $headers = [],
        ?Throwable $previous = null,
        int $code = 0
    ) {
        parent::__construct(400, $errorCode, $message, $detail, $headers, $previous, $code);
    }

    public static function invalidJson(?Throwable $previous = null): self
    {
        return new self('invalid_json', 'The request body must contain valid JSON.', previous: $previous);
    }

    public static function missingRequiredFields(array $fields, ?Throwable $previous = null): self
    {
        return new self('missing_required_fields', 'Required fields are missing.', [
            'fields' => $fields
        ], previous: $previous);
    }

    /**
     * @param list<string> $fields
     */
    public static function unknownFields(array $fields, ?Throwable $previous = null): self
    {
        return new self(
            'unknown_fields',
            'The request body contains unknown fields.',
            ['fields' => array_values($fields)],
            previous: $previous,
        );
    }

    public static function invalidFieldType(?string $field, ?Throwable $previous = null): self
    {
        return new self(
            'invalid_field_type',
            'A request field has an invalid type.',
            ['field' => $field],
            previous: $previous,
        );
    }

    public static function invalidFieldValue(?string $field, ?Throwable $previous = null): self
    {
        return new self(
            'invalid_field_value',
            'A request field contains an invalid value.',
            ['name' => $field],
            previous: $previous,
        );
    }

    public static function invalidField(?string $field, ?Throwable $previous = null): self
    {
        return new self(
            'invalid_field',
            'A request field could not be processed.',
            ['field' => $field],
            previous: $previous,
        );
    }
}
