<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Type;

use Movioza\Identity\Domain\ValueObject\GuestID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class GuestIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'identity_guest_id';

    protected function getValueObjectClass(): string
    {
        return GuestID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
