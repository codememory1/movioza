<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Type;

use Movioza\Identity\Domain\ValueObject\GuestSessionToken;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class GuestSessionTokenType extends AbstractStringValueObjectType
{
    public const string NAME = 'identity_guest_session_token';

    protected function getValueObjectClass(): string
    {
        return GuestSessionToken::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
