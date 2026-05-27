<?php

declare(strict_types=1);

namespace Movioza\Attribute\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * @template T of AttributeInterface
 */
interface AttributeHandlerInterface
{
    /**
     * @param T $attribute
     */
    public function handle(AttributeInterface $attribute, Request $request, int $requestType, bool $mainRequest): void;
}
