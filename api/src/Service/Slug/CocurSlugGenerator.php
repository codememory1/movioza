<?php

declare(strict_types=1);

namespace Movioza\Service\Slug;

use Cocur\Slugify\RuleProvider\RuleProviderInterface;
use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;

class CocurSlugGenerator implements SlugGeneratorInterface
{
    private readonly SlugifyInterface $slugify;

    public function __construct(array $options = [], ?RuleProviderInterface $provider = null)
    {
        $this->slugify = new Slugify($options, $provider);
    }

    public function generate(string $text): string
    {
        return $this->slugify->slugify($text);
    }
}
