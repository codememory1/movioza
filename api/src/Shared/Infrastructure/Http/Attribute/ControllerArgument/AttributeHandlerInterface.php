<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Attribute\ControllerArgument;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * @template T of AttributeInterface
 */
interface AttributeHandlerInterface
{
    /**
     * @param T $attribute
     */
    public function handle(AttributeInterface $attribute, Request $request, ArgumentMetadata $argument): mixed;
}
