<?php

declare(strict_types = 1);

namespace Movioza\Identity\Application\Command\CreateGuestSession;

use Movioza\Identity\Application\Identity\GuestIDGeneratorInterface;
use Movioza\Identity\Application\Policy\GuestSessionLifetimePolicy;
use Movioza\Identity\Application\Security\GuestSessionTokenGeneratorInterface;
use Movioza\Identity\Domain\Entity\Guest;
use Movioza\Identity\Domain\Entity\GuestSession;
use Movioza\Identity\Domain\Repository\GuestRepositoryInterface;
use Movioza\Identity\Domain\Repository\GuestSessionRepositoryInterface;
use Movioza\Identity\Domain\ValueObject\GuestID;
use Movioza\Shared\Application\Bus\Command\CommandHandlerInterface;
use Movioza\Shared\Application\Clock\ClockInterface;
use Movioza\Shared\Application\Transaction\TransactionManagerInterface;

final readonly class CreateGuestSessionCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private GuestIDGeneratorInterface $guestIDGenerator,
        private ClockInterface $clock,
        private GuestSessionTokenGeneratorInterface $guestSessionTokenGenerator,
        private GuestSessionLifetimePolicy $lifetimePolicy,
        private GuestRepositoryInterface $guestRepository,
        private GuestSessionRepositoryInterface $guestSessionRepository,
        private TransactionManagerInterface $tm
    ) {
    }

    public function __invoke(CreateGuestSessionCommand $command): void
    {
        $this->tm->transactional(function () use ($command) {
            $guestID = new GuestID($this->guestIDGenerator->next());
            $createdAt = $this->clock->now();

            $this->guestRepository->save(new Guest($guestID, $createdAt));
            $this->guestSessionRepository->save(new GuestSession(
                $command->id,
                $guestID,
                $this->guestSessionTokenGenerator->generate(),
                $command->ip,
                $command->userAgent,
                $command->locale,
                $command->timezone,
                $this->lifetimePolicy->expiresAt($createdAt),
                $createdAt
            ));
        });
    }
}
