<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Movioza\Shared\Domain\Entity\Country;
use Movioza\Shared\Domain\Repository\CountryRepositoryInterface;

final readonly class DoctrineCountryRepository implements CountryRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }

    public function save(Country $country): void
    {
        $this->em->persist($country);
    }
}
