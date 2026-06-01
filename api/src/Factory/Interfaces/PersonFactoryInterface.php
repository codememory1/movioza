<?php

declare(strict_types=1);

namespace Movioza\Factory\Interfaces;

use DateTimeImmutable;
use Movioza\Entity\Interfaces\PersonInterface;
use Movioza\Enum\PersonGender;

interface PersonFactoryInterface
{
    public function create(string $name, string $biography, ?PersonGender $gender, ?DateTimeImmutable $birthDate): PersonInterface;
}
