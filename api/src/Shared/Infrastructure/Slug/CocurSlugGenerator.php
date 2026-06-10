<?php

declare(strict_types=1);

namespace Movioza\Shared\Infrastructure\Slug;

use Cocur\Slugify\RuleProvider\RuleProviderInterface;
use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;
use Movioza\Shared\Domain\Slug\SlugGeneratorInterface;

readonly class CocurSlugGenerator implements SlugGeneratorInterface
{
    private SlugifyInterface $slugify;

    public function __construct(array $options = [], ?RuleProviderInterface $provider = null)
    {
        $this->slugify = new Slugify($options, $provider);
    }

    public function generate(string $text): string
    {
        return $this->slugify->slugify($text);
    }
}
