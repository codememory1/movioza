<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Identity;

interface GuestSessionIDGeneratorInterface
{
    public function next(): string;
}
