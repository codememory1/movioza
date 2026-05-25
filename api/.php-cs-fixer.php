<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
        ->in(__DIR__ . '/src')
        ->in(__DIR__ . '/tests')
        ->exclude(['var', 'vendor', 'public', 'migrations']);

return (new Config())
    ->setRiskyAllowed(true)
    ->setIndent('    ')
    ->setRules([
        '@Symfony' => true,

        'declare_strict_types' => true,
        'strict_comparison' => true,
        'strict_param' => true,

        'array_syntax' => ['syntax' => 'short'],
        'list_syntax' => ['syntax' => 'short'],
        'single_quote' => true,

        'fully_qualified_strict_types' => [
            'import_symbols' => true,
        ],

        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => false,
        ],

        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
        ],

        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_superfluous_phpdoc_tags' => true,

        'trailing_comma_in_multiline' => true,
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
        ],

        'concat_space' => [
            'spacing' => 'one',
        ],

        'yoda_style' => false,
        'modernize_strpos' => true,
        'use_arrow_functions' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'native_function_casing' => true,
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
            ],
        ],
    ])
    ->setFinder($finder);
