<?php

use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

return (new PhpCsFixer\Config())
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setCacheFile(__DIR__ . '/var/cache/.php_cs')
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in([
                __DIR__ . '/example',
                __DIR__ . '/src',
                __DIR__ . '/tests',
            ])
            ->append([
                __FILE__,
            ]),
    )
    ->setRules([
        '@PER-CS' => true,
        '@PER-CS:risky' => true,

        // Import
        'global_namespace_import' => true,
        'ordered_imports' => true,
        'no_unused_imports' => true,
    ])
;
