<?php

declare(strict_types=1);

namespace Movioza\Shared\Presentation\Http\Formatter;

use Movioza\Shared\Presentation\Http\Response\ResponseBuilderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @template T of ResponseBuilderInterface
 */
interface ResponseFormatterInterface
{
    public function supports(ResponseBuilderInterface $builder): bool;

    /**
     * @param T $builder
     */
    public function format(ResponseBuilderInterface $builder): Response;
}
