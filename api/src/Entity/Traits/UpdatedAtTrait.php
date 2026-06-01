<?php

declare(strict_types=1);

namespace Movioza\Entity\Traits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Movioza\Serializer\Group\MediaGroups;
use Symfony\Component\Serializer\Attribute\Groups;

trait UpdatedAtTrait
{
    #[ORM\Column(nullable: true)]
    #[Groups([MediaGroups::DETAIL])]
    protected ?DateTimeImmutable $updatedAt = null;

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = new DateTimeImmutable();

        return $this;
    }
}
