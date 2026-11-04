<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Type;

use Movioza\Identity\Domain\ValueObject\GuestSessionID;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class GuestSessionIDType extends AbstractStringValueObjectType
{
    public const string NAME = 'identity_guest_session_id';

    protected function getValueObjectClass(): string
    {
        return GuestSessionID::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
