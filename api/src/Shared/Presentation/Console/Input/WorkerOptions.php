<?php

declare(strict_types = 1);

namespace Movioza\Shared\Presentation\Console\Input;

use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Style\SymfonyStyle;

final class WorkerOptions
{
    #[Option(
        description: 'Maximum number of items fetched per batch.',
        name: 'batch-size'
    )]
    public int $batchSize = 50;

    #[Option(
        description: 'Pause in seconds when there are no pending items.',
        name: 'sleep'
    )]
    public int $sleep = 5;

    #[Option(
        description: 'Process one batch and exit.',
        name: 'once'
    )]
    public bool $once = false;

    public function validate(SymfonyStyle $io): bool
    {
        if ($this->batchSize < 1) {
            $io->error('The --batch-size option must be greater than zero.');

            return false;
        }

        if ($this->sleep < 1) {
            $io->error('The --sleep option must be greater than zero.');

            return false;
        }

        return true;
    }
}
