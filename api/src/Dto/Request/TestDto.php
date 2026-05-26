<?php

declare(strict_types=1);

namespace Movioza\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class TestDto
{
    public function __construct(
        #[Assert\Length(max: 5, maxMessage: 'Некорректная длина имени')]
        public string $name,
        public string $surname,
        public int $age,
        public bool $isMale,
        public ?string $description = null
    ) {
    }
}
