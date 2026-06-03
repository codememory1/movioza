<?php

declare(strict_types=1);

namespace Movioza\Controller\Api\Public;

use Movioza\Api\Response\ApiResponseInterface;
use Movioza\Attribute\Controller\Cacheable;
use Movioza\Attribute\ControllerArgument\MapEntity;
use Movioza\Entity\Interfaces\MediaInterface;
use Movioza\Entity\Media;
use Movioza\Serializer\Group\MediaGroups;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/media', name: 'media_')]
readonly class MediaController
{
    public function __construct(
        private ApiResponseInterface $apiResponse
    ) {
    }

    #[Route(path: '/{id<\d+>}', name: 'detail')]
    #[Cacheable(key: 'api:media:{id}:detail', ttl: 86400)]
    public function detail(
        #[MapEntity(Media::class)]
        MediaInterface $media
    ): ApiResponseInterface {
        return $this->apiResponse
            ->success(200)
            ->resource($media, [MediaGroups::DETAIL]);
    }
}
