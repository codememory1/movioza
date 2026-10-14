<?php

declare(strict_types = 1);

namespace Movioza\MediaProcessing\Infrastructure\Doctrine\Type;

use Movioza\MediaProcessing\Domain\ValueObject\PageUrl;
use Movioza\Shared\Infrastructure\Doctrine\Type\AbstractStringValueObjectType;

final class PageUrlType extends AbstractStringValueObjectType
{
    public const string NAME = 'media_processing_page_url';

    protected function getValueObjectClass(): string
    {
        return PageUrl::class;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
