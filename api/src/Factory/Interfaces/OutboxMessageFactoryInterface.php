<?php

declare(strict_types=1);

namespace Movioza\Factory\Interfaces;

use Movioza\Entity\Interfaces\OutboxMessageInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

interface OutboxMessageFactoryInterface
{
    /**
     * @throws ExceptionInterface
     */
    public function create(object $message): OutboxMessageInterface;

    /**
     * @throws ExceptionInterface
     */
    public function reconstitute(OutboxMessageInterface $message): object;
}
