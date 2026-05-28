<?php

declare(strict_types=1);

namespace Movioza\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait BigintIdentifier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::BIGINT, insertable: false, updatable: false, generated: 'INSERT')]
    public private(set) int $id {
        get => $this->id;
    }
}
