<?php

declare(strict_types = 1);

namespace Movioza\People\Domain\Enum;

enum PersonGender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
}
