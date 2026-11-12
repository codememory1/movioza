<?php

declare(strict_types = 1);

namespace Movioza\Catalog\Presentation\Http\Response;

use Movioza\Catalog\Application\ReadModel\EpisodeListItemView;

final readonly class ListSeasonEpisodesResponse
{
    /**
     * @param list<EpisodeListItemView> $episodes
     */
    public function __construct(
        private array $episodes
    ) {
    }

    public function toArray(): array
    {
        return array_map(static fn (EpisodeListItemView $episode) => [
            'number' => $episode->number,
            'slug' => $episode->slug,
            'title' => $episode->title,
            'description' => $episode->description,
            'duration' => $episode->duration,
            'preview' => $episode->preview === null ? null : [
                'url' => $episode->preview->url,
                'blur_hash' => $episode->preview->blurHash,
                'width' => $episode->preview->width,
                'height' => $episode->preview->height
            ],
            'released_at' => $episode->releasedAt->getTimestamp(),
            'uploaded_at' => $episode->uploadedAt->getTimestamp(),
            'updated_at' => $episode->updatedAt?->getTimestamp()
        ], $this->episodes);
    }
}
