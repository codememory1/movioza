<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

use LogicException;

final class DataDocument implements DocumentInterface
{
    /** @var array<string, mixed> */
    private array $body;

    public function __construct(mixed $data, DocumentMemberInterface ...$members)
    {
        $this->body = [
            'data' => $data,
        ];

        foreach ($members as $member) {
            if (array_key_exists($member->getName(), $this->body)) {
                throw new LogicException(sprintf(
                    'Document member "%s" is already defined.',
                    $member->getName()
                ));
            }

            $this->body[$member->getName()] = $member->getValue();
        }
    }

    public function jsonSerialize(): object
    {
        return (object) $this->body;
    }
}
