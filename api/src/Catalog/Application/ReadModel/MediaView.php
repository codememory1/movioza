<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Application\ReadModel;

use DateTimeImmutable;
use Movioza\Catalog\Domain\Enum\MediaKind;
use Movioza\Catalog\Domain\Enum\MediaType;
use Movioza\Shared\Application\ReadModel\CountryView;

final readonly class MediaView
{
    /**
     * @param CountryView[] $countries
     */
    public function __construct(
        public int $id,
        public MediaType $type,
        public ?MediaKind $kind,
        public string $title,
        public string $slug,
        public string $description,
        public int $ageRestrictions,
        public array $countries,
        public ImageView $logo,
        public ImageView $poster,
        public ImageView $backdrop,
        public ?DateTimeImmutable $releasedDate = null,
        public ?DateTimeImmutable $uploadedAt = null,
        public ?DateTimeImmutable $updatedAt = null
    ) {
    }
}
