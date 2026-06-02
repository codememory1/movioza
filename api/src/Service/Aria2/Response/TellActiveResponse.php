<?php

declare(strict_types=1);

namespace Movioza\Service\Aria2\Response;

readonly class TellActiveResponse extends RPCResponse
{
    /**
     * @return string[]
     */
    public function getGIDs(): array
    {
        $result = [];

        foreach ($this->response['result'] as $item) {
            $result[] = $item['gid'];
        }

        return $result;
    }
}
