<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Response;

use Movioza\Catalog\Application\ReadModel\GenreView;
use Movioza\Catalog\Application\ReadModel\ImageView;
use Movioza\Catalog\Application\ReadModel\MediaView;
use Movioza\Catalog\Application\ReadModel\PersonView;
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
            'slug' => $this->mediaView->slug,
            'type' => $this->mediaView->type->value,
            'kind' => $this->mediaView->kind?->value,
            'title' => $this->mediaView->title,
            'description' => $this->mediaView->description,
            'age_restrictions' => $this->mediaView->ageRestrictions,
            'logo' => $this->mediaView->logo->url,
            'poster' => $this->mapImage($this->mediaView->logo),
            'backdrop' => $this->mapImage($this->mediaView->backdrop),
            'countries' => array_map(fn (CountryView $country) => $country->name, $this->mediaView->countries),
            'genres' => array_map(fn (GenreView $genre) => [
                'id' => $genre->id,
                'slug' => $genre->slug,
                'name' => $genre->name
            ], $this->mediaView->genres),
            'directors' => $this->mapPersons($this->mediaView->directors),
            'actors' => $this->mapPersons($this->mediaView->actors),
            'released_at' => $this->mediaView->releasedDate?->getTimestamp(),
            'uploaded_at' => $this->mediaView->uploadedAt?->getTimestamp(),
            'updated_at' => $this->mediaView->updatedAt?->getTimestamp()
        ];
    }

    private function mapImage(ImageView $image): array
    {
        return [
            'url' => $image->url,
            'blur_hash' => $image->blurHash,
            'width' => $image->width,
            'height' => $image->height
        ];
    }

    /**
     * @param PersonView[] $persons
     */
    private function mapPersons(array $persons): array
    {
        return array_map(fn (PersonView $person) => [
            'id' => $person->id,
            'slug' => $person->slug,
            'name' => $person->name
        ], $persons);
    }
}
