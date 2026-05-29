<?php

declare(strict_types=1);

namespace Movioza\Entity\Interfaces;

use Doctrine\Common\Collections\Collection;
use Movioza\Enum\StorageType;

interface FileInterface extends BigintIdentifierInterface
{
    public StorageType $storage {
        get;
    }

    public string $bucket {
        get;
    }

    public string $key {
        get;
    }

    public string $extension {
        get;
    }

    public int $size {
        get;
    }

    public Collection $images {
        get;
    }
}
