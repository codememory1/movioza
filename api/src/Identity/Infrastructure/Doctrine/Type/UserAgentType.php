<?php

declare(strict_types = 1);

namespace Movioza\Identity\Infrastructure\Doctrine\Type;

use Movioza\Identity\Domain\ValueObject\UserAgent;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class UserAgentType extends AbstractStringValueObjectType
{
    public const string NAME = 'identity_user_agent';

    protected function getValueObjectClass(): string
    {
        return UserAgent::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
