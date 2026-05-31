<?php

declare(strict_types=1);

namespace Movioza\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Serializer\Group\GenreGroups;
use Symfony\Component\Serializer\Attribute\Groups;

trait BigintIdentifier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::BIGINT, insertable: false, updatable: false, generated: 'INSERT')]
    #[Groups([GenreGroups::LIST])]
    public private(set) int $id {
        get => $this->id;
    }
}
