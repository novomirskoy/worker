<?php

return (new PhpCsFixer\Config())
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
            ])
    )
    ->setRules([
        '@PSR12' => true,
        '@PSR12:risky' => true,
    ])
;
