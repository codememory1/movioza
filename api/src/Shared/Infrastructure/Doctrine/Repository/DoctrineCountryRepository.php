<?php

declare(strict_types = 1);

namespace Movioza\Shared\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ManagerRegistry;
use Movioza\Shared\Domain\Entity\Country;
use Movioza\Shared\Domain\Repository\CountryRepositoryInterface;

class DoctrineCountryRepository extends ServiceEntityRepository implements CountryRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @throws Exception
     */
    public function update(Country $country): bool
    {
        $affectedRows = $this->getEntityManager()->getConnection()->update('countries', [
            'name' => $country->getName(),
            'code' => $country->getCode(),
        ], [
            'id' => $country->getId()->getValue()
        ]);

        return $affectedRows > 0;
    }

    public function save(Country $country): void
    {
        $this->getEntityManager()->persist($country);
    }
}
