<?php

declare(strict_types=1);

namespace Movioza\Exception;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Throwable;

class BadRequestException extends HttpException
{
    public function __construct(string $message, array $headers = [], ?Throwable $previous = null)
    {
        parent::__construct(400, $message, $headers, $previous);
    }

    public static function invalidRequestPayload(?Throwable $previous = null): self
    {
        return new self('The request payload is invalid.', previous: $previous);
    }

    /**
     * @param string[] $fields
     */
    public static function missingRequiredFields(array $fields, NameConverterInterface $nameConverter, ?Throwable $previous = null): self
    {
        $fields = array_map(static fn (string $field): string => $nameConverter->normalize($field), $fields);

        return new self(sprintf(
            'The following required fields are missing: %s.',
            implode(', ', $fields)
        ), previous: $previous);
    }

    /**
     * @param string[] $fields
     */
    public static function extraFields(array $fields, NameConverterInterface $nameConverter, ?Throwable $previous = null): self
    {
        $fields = array_map(static fn (string $field): string => $nameConverter->normalize($field), $fields);

        return new self(sprintf(
            'Unknown fields were provided: %s.',
            implode(', ', $fields)
        ), previous: $previous);
    }

    public static function invalidFieldValue(string $field, NameConverterInterface $nameConverter, ?Throwable $previous = null): self
    {
        return new self("Invalid value for field \"{$nameConverter->normalize($field)}\".", previous: $previous);
    }
}
