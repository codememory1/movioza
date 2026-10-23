<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Http\Response;

use LogicException;

final readonly class Metadata implements DocumentMemberInterface
{
    /**
     * @var DocumentMemberInterface[]
     */
    private array $members;

    public function __construct(DocumentMemberInterface ...$members)
    {
        $this->members = $members;
    }

    public function getName(): string
    {
        return 'meta';
    }

    public function getValue(): object
    {
        $body = [];

        foreach ($this->members as $member) {
            if (array_key_exists($member->getName(), $body)) {
                throw new LogicException(sprintf(
                    'Document member "%s" is already defined.',
                    $member->getName()
                ));
            }

            $body[$member->getName()] = $member->getValue();
        }

        return (object) $body;
    }
}
