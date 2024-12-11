<?php

declare(strict_types=1);

namespace Tests\Novomirskoy\Worker\Extension;

use DateMalformedStringException;
use DateTimeImmutable;
use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\Extension\LimitTimeExtension;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class LimitTimeExtensionTest extends TestCase
{
    public function testExtension(): void
    {
        $context = new Context(new NullLogger());
        $extension = new LimitTimeExtension(new DateTimeImmutable());

        $extension->onBeforeRunning($context);
        static::assertTrue($context->isExecutionInterrupted());
    }

    public function testExtensionWithDateTimeAsString(): void
    {
        $context = new Context(new NullLogger());
        $extension = new LimitTimeExtension('now');

        $extension->onBeforeRunning($context);
        static::assertTrue($context->isExecutionInterrupted());
    }

    public function testExtensionWithInvalidDateTimeAsString(): void
    {
        $this->expectException(DateMalformedStringException::class);
        new LimitTimeExtension('nowww');
    }
}
