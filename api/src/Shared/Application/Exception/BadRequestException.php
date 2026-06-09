<?php

declare(strict_types = 1);

namespace Movioza\Shared\Application\Exception;

use Throwable;

class BadRequestException extends AbstractApiException
{
    private const string ERROR_CODE = 'BAD_REQUEST';

    public function __construct(string $message, array $headers = [], int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, 404, self::ERROR_CODE, $headers, $code, $previous);
    }

    public static function invalidRequestPayload(?Throwable $previous = null): self
    {
        return new self('The request payload is invalid.', previous: $previous);
    }

    /**
     * @param string[] $fields
     */
    public static function missingRequiredFields(array $fields, ?Throwable $previous = null): self
    {
        return new self(sprintf(
            'The following required fields are missing: %s.',
            implode(', ', $fields)
        ), previous: $previous);
    }

    /**
     * @param string[] $fields
     */
    public static function extraFields(array $fields, ?Throwable $previous = null): self
    {
        return new self(sprintf(
            'Unknown fields were provided: %s.',
            implode(', ', $fields)
        ), previous: $previous);
    }

    public static function invalidFieldValue(string $field, ?Throwable $previous = null): self
    {
        return new self("Invalid value for field \"$field\".", previous: $previous);
    }
}
