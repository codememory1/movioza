<?php

declare(strict_types = 1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])

    ->withSkip([
        __DIR__ . '/migrations',
        __DIR__ . '/var',
        __DIR__ . '/vendor',
    ])

    ->withPhpSets(php84: true)

    ->withSets([
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::TYPE_DECLARATION
    ])

    ->withComposerBased(twig: true, doctrine: true, phpunit: true, symfony: true)

    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml');
