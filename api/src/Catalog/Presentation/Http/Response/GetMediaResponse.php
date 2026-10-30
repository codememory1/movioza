<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Response;

use Movioza\Catalog\Application\ReadModel\MediaView;
use Movioza\Shared\Application\ReadModel\CountryView;

final readonly class GetMediaResponse
{
    public function __construct(
        private MediaView $mediaView
    ) {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->mediaView->id,
            'type' => $this->mediaView->type->value,
            'kind' => $this->mediaView->kind?->value,
            'slug' => $this->mediaView->slug,
            'title' => $this->mediaView->title,
            'description' => $this->mediaView->description,
            'age_restrictions' => $this->mediaView->ageRestrictions,
            'countries' => array_map(fn (CountryView $country) => $country->name, $this->mediaView->countries),
            'logo' => $this->mediaView->logo->url,
            'poster' => [
                'url' => $this->mediaView->poster->url,
                'blur_hash' => $this->mediaView->poster->blurHash,
                'width' => $this->mediaView->poster->width,
                'height' => $this->mediaView->poster->height
            ],
            'backdrop' => [
                'url' => $this->mediaView->backdrop->url,
                'blur_hash' => $this->mediaView->backdrop->blurHash,
                'width' => $this->mediaView->backdrop->width,
                'height' => $this->mediaView->backdrop->height
            ],
            'released_at' => $this->mediaView->releasedDate?->getTimestamp(),
            'uploaded_at' => $this->mediaView->uploadedAt?->getTimestamp(),
            'updated_at' => $this->mediaView->updatedAt?->getTimestamp()
        ];
    }
}
