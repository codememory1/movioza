<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Identity;

interface GuestIDGeneratorInterface
{
    public function next(): string;
}
