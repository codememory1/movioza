<?php

declare(strict_types = 1);

namespace Movioza\Discussion\Application\Identity;

interface CommentIDGeneratorInterface
{
    public function next(): int;
}
