<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Movioza\MediaProcessing\Domain\Repository\MediaSourceRepositoryInterface;

readonly class DoctrineMediaSourceRepository implements MediaSourceRepositoryInterface
{
    public function __construct(
        private EntityManagerInterface $em
    ) {
    }
}
