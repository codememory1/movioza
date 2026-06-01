<?php

declare(strict_types=1);

namespace Movioza\Attribute\Controller;

use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * @template T of AttributeInterface
 */
interface AttributeHandlerInterface
{
    /**
     * @param T $attribute
     */
    public function handle(AttributeInterface $attribute, ControllerEvent $event): void;
}
