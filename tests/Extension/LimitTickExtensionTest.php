<?php

declare(strict_types=1);

namespace Tests\Novomirskoy\Worker\Extension;

use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\Extension\LimitTickExtension;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class LimitTickExtensionTest extends TestCase
{
    public function testExtension(): void
    {
        $context = new Context(new NullLogger());
        $extension = new LimitTickExtension(1);

        $extension->onBeforeRunning($context);
        static::assertFalse($context->isExecutionInterrupted());
    }
}
