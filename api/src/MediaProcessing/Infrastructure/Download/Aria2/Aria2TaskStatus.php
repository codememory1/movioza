<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Download\Aria2;

use UnexpectedValueException;

class Aria2TaskStatus
{
    /**
     * @param list<string> $followedBy
     * @param list<array{
     *     index: string,
     *     path: string,
     *     length: string,
     *     completedLength: string,
     *     selected: string
     * }> $files
     */
    public function __construct(
        public string $gid,
        public string $status,
        public int $totalLength,
        public int $completedLength,
        public int $downloadSpeed,
        public array $followedBy,
        public array $files,
        public bool $seeder,
        public ?string $errorMessage,
    ) {
    }

    public static function fromArray(array $data): Aria2TaskStatus
    {
        $expectedKeys = [
            'gid',
            'completedLength',
            'totalLength',
            'downloadSpeed',
            'status'
        ];

        foreach ($expectedKeys as $expectedKey) {
            if (!isset($data[$expectedKey])) {
                throw new UnexpectedValueException(sprintf('Missing aria2 status field: "%s".', $expectedKey));
            }
        }

        return new self(
            gid: $data['gid'],
            status: $data['status'],
            totalLength: (int) $data['totalLength'],
            completedLength: (int) $data['completedLength'],
            downloadSpeed: (int) $data['downloadSpeed'],
            followedBy: array_values($data['followedBy'] ?? []),
            files: array_values($data['files'] ?? []),
            seeder: ($data['seeder'] ?? 'false') === 'true',
            errorMessage: $data['errorMessage'] ?? null,
        );
    }
}
