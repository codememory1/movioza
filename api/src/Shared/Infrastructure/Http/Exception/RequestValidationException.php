<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class RequestValidationException extends HttpException
{
    public function __construct(
        mixed $detail = null,
        array $headers = [],
        ?Throwable $previous = null,
        int $code = 0
    ) {
        parent::__construct(
            422,
            'validation_failed',
            'Request validation failed.',
            $detail,
            $headers,
            $previous,
            $code
        );
    }

    public static function fromViolations(ConstraintViolationListInterface $violations): self
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = [
                'path' => $violation->getPropertyPath(),
                'code' => 'invalid_field_value',
                'message' => $violation->getMessage(),
            ];
        }

        return new self($errors);
    }
}
