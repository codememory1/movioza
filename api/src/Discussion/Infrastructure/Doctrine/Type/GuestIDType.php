<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Infrastructure\Doctrine\Type;

use Movioza\Discussion\Domain\ValueObject\GuestID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class GuestIDType extends AbstractStringValueObjectType
{
    private const string NAME = 'discussion_guest_id';

    protected function getValueObjectClass(): string
    {
        return GuestID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
