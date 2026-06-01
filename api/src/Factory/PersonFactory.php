<?php

declare(strict_types=1);

namespace Movioza\Factory;

use DateTimeImmutable;
use Movioza\Entity\Interfaces\PersonInterface;
use Movioza\Entity\Person;
use Movioza\Enum\PersonGender;
use Movioza\Factory\Interfaces\PersonFactoryInterface;
use Movioza\Service\Slug\SlugGeneratorInterface;

readonly class PersonFactory implements PersonFactoryInterface
{
    public function __construct(
        private SlugGeneratorInterface $slugGenerator
    ) {
    }

    public function create(string $name, string $biography, ?PersonGender $gender, ?DateTimeImmutable $birthDate): PersonInterface
    {
        return new Person(
            $name,
            $this->slugGenerator->generate($name),
            $biography,
            $gender,
            $birthDate
        );
    }
}
