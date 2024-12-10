<?php

declare(strict_types=1);

namespace Tests\Novomirskoy\Worker\Extension;

use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\Extension\SignalExtension;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class SignalExtensionTest extends TestCase
{
    #[DataProvider(methodName: 'signalsProvider')]
    public function testHandleSignal(int $signal, bool $result): void
    {
        $context = new Context(new NullLogger());
        $extension = new SignalExtension();
        $extension->onStart($context);

        $extension->handleSignal($signal);
        $extension->onBeforeRunning($context);
        static::assertEquals($context->isExecutionInterrupted(), $result);

        $extension->handleSignal($signal);
        $extension->onAfterRunning($context);
        static::assertEquals($context->isExecutionInterrupted(), $result);

        $extension->handleSignal($signal);
        $extension->onIdle($context);
        static::assertEquals($context->isExecutionInterrupted(), $result);
    }

    public static function signalsProvider(): iterable
    {
        yield [SIGTERM, true];
        yield [SIGQUIT, true];
        yield [SIGINT, true];
        yield [SIGILL, false];
        yield [SIGPIPE, false];
        yield [SIGCLD, false];
    }
}
