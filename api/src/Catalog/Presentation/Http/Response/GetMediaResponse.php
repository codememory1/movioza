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

    /**
     * Serializes media details; date-time fields use Unix seconds.
     *
     * @return array{
     *     id: int,
     *     slug: string,
     *     type: string,
     *     kind: string|null,
     *     title: string,
     *     description: string,
     *     age_restrictions: int,
     *     logo: string,
     *     poster: array{url: string, blur_hash: string, width: int, height: int},
     *     backdrop: array{url: string, blur_hash: string, width: int, height: int},
     *     countries: list<string>,
     *     genres: list<array{id: int, slug: string, name: string}>,
     *     directors: list<array{id: int, slug: string, name: string}>,
     *     actors: list<array{id: int, slug: string, name: string}>,
     *     released_at: int|null,
     *     uploaded_at: int|null,
     *     updated_at: int|null
     * }
     */
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
            'poster' => $this->mapImage($this->mediaView->poster),
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

    /**
     * @return array{url: string, blur_hash: string, width: int, height: int}
     */
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
     * @param list<PersonView> $persons
     * @return list<array{id: int, slug: string, name: string}>
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
