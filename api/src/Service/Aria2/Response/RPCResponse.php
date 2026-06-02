<?php

declare(strict_types=1);

namespace Movioza\Service\Aria2\Response;

readonly class RPCResponse
{
    public function __construct(
        public array $response
    ) {
    }

    public function getId(): string
    {
        return $this->response['id'];
    }
}
