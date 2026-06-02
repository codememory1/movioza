<?php

declare(strict_types=1);

namespace Movioza\Service\Aria2\Response;

readonly class TellStatusResponse extends RPCResponse
{
    private const string ACTIVE_STATUS = 'active';
    private const string WAITING_STATUS = 'waiting';
    private const string PAUSED_STATUS = 'paused';
    private const string ERROR_STATUS = 'error';
    private const string REMOVED_STATUS = 'removed';
    private const string COMPLETE_STATUS = 'complete';

    public function getBitfield(): string
    {
        return $this->response['result']['bitfield'];
    }

    /**
     * @return string[]
     */
    public function getTrackers(): array
    {
        $result = [];

        foreach ($this->response['result']['bittorrent']['announceList'] as $group) {
            foreach ($group as $announce) {
                $result[] = $announce;
            }
        }

        return $result;
    }

    public function getCompletedLength(): string
    {
        return $this->response['result']['completedLength'];
    }

    public function getTotalLength(): string
    {
        return $this->response['result']['totalLength'];
    }

    public function getDownloadPercent(): float
    {
        return $this->getCompletedLength() / $this->getTotalLength() * 100;
    }

    public function getDownloadSpeed(): string
    {
        return $this->response['result']['downloadSpeed'];
    }

    /**
     * @return string[]
     */
    public function getFollowedBy(): array
    {
        return $this->response['result']['followedBy'];
    }

    public function getStatus(): string
    {
        return $this->response['result']['status'];
    }

    public function isActive(): bool
    {
        return $this->getStatus() === self::ACTIVE_STATUS;
    }

    public function isWaiting(): bool
    {
        return $this->getStatus() === self::WAITING_STATUS;
    }

    public function isPaused(): bool
    {
        return $this->getStatus() === self::PAUSED_STATUS;
    }

    public function isError(): bool
    {
        return $this->getStatus() === self::ERROR_STATUS;
    }

    public function isRemoved(): bool
    {
        return $this->getStatus() === self::REMOVED_STATUS;
    }

    public function isComplete(): bool
    {
        return $this->getStatus() === self::COMPLETE_STATUS;
    }
}
