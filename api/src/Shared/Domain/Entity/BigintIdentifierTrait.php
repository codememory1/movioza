<?php

declare(strict_types=1);

namespace Movioza\Shared\Domain\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait BigintIdentifierTrait
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::BIGINT, insertable: false, updatable: false, generated: 'INSERT')]
    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
