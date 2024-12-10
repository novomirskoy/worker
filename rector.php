<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/example',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withSets([
        SetList::PHP_73,
        SetList::PHP_74,
        SetList::PHP_80,
        SetList::PHP_81,
        SetList::PHP_82,
        SetList::PHP_83,
        SetList::DEAD_CODE,
    ])
    ->withSkip([
        ClassPropertyAssignToConstructorPromotionRector::class,
    ])
    ->withTypeCoverageLevel(0);
