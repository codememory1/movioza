<?php

declare(strict_types=1);

namespace Movioza\Service\Aria2\Response;

readonly class AddTorrentResponse extends RPCResponse
{
    public function getGID(): string
    {
        return $this->response['result'];
    }
}
