<?php

declare(strict_types=1);

namespace Movioza\Service\Aria2\Response;

readonly class RemoveResponse extends RPCResponse
{
    public function getGID(): string
    {
        return $this->response['result']['gid'];
    }
}
