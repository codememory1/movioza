<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Bus;

use Movioza\Shared\Application\Bus\Query\QueryBusInterface;
use Movioza\Shared\Application\Bus\Query\QueryInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class SymfonyQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @throws Throwable
     */
    public function ask(QueryInterface $query): mixed
    {
        try {
            return $this->handle($query);
        } catch (HandlerFailedException $e) {
            $exceptions = array_values($e->getWrappedExceptions(recursive: true));

            if (count($exceptions) === 1) {
                throw $exceptions[0];
            }

            throw $e;
        }
    }
}
