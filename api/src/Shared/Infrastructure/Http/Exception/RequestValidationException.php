<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Exception;

use Movioza\Shared\Infrastructure\Http\Error\ErrorExtensionsProviderInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

final class RequestValidationException extends UnprocessableEntityHttpException implements ErrorExtensionsProviderInterface
{
    /**
     * @param list<array{field: string, message: string}> $errors
     */
    private function __construct(
        private readonly array $errors
    ) {
        parent::__construct('Request payload validation failed.');
    }

    public static function fromViolations(ConstraintViolationListInterface $violations): self
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = [
                'field' => $violation->getPropertyPath(),
                'message' => (string) $violation->getMessage(),
            ];
        }

        return new self($errors);
    }

    /**
     * @return array{errors: list<array{field: string, message: string}>}
     */
    public function getErrorExtensions(): array
    {
        return [
            'errors' => $this->errors,
        ];
    }
}
