<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\Symfony\Attribute\ControllerArgument;

use Movioza\Attribute\ControllerArgument\AttributeInterface as T;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * @template T of object
 */
interface AttributeHandlerInterface
{
    /**
     * @return class-string<T>
     */
    public static function getSupportedAttribute(): string;

    /**
     * @param T $attribute
     */
    public function handle(object $attribute, Request $request, ArgumentMetadata $argument): mixed;
}
