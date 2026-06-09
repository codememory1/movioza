<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\Symfony\Attribute\Controller;

use Movioza\Shared\Attribute\Controller\AttributeInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * @template T of AttributeInterface
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
    public function handle(AttributeInterface $attribute, ControllerEvent $event): void;
}
