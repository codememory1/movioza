<?php

declare(strict_types=1);

namespace Movioza\Attribute\ControllerArgument;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

interface AttributeHandlerInterface
{
    public function handle(AttributeInterface $attribute, Request $request, ArgumentMetadata $argument): mixed;
}
