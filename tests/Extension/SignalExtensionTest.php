<?php

declare(strict_types=1);

namespace Tests\Novomirskoy\Worker\Extension;

use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\Extension\SignalExtension;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;

class SignalExtensionTest extends TestCase
{
    /**
     * @dataProvider signalsProvider
     */
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

    public function signalsProvider(): array
    {
        return [
            [SIGTERM, true],
            [SIGQUIT, true],
            [SIGINT, true],
            [SIGILL, false],
            [SIGPIPE, false],
            [SIGCLD, false],
        ];
    }
}
