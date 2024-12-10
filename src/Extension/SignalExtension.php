<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Extension;

use LogicException;
use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;
use Override;
use Psr\Log\LoggerInterface;

final class SignalExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    private bool $interrupt = false;

    private LoggerInterface $logger;

    #[Override]
    public function onStart(Context $context): void
    {
        if (false === extension_loaded('pcntl')) {
            throw new LogicException('The pcntl extension is required in order to catch signals'); // @codeCoverageIgnore
        }

        pcntl_async_signals(true);

        pcntl_signal(SIGTERM, $this->handleSignal(...));
        pcntl_signal(SIGQUIT, $this->handleSignal(...));
        pcntl_signal(SIGINT, $this->handleSignal(...));

        $this->interrupt = false;

        $this->logger = $context->logger;
    }

    #[Override]
    public function onBeforeRunning(Context $context): void
    {
        $this->interruptExecutionIfNeeded($context);
    }

    #[Override]
    public function onAfterRunning(Context $context): void
    {
        $this->interruptExecutionIfNeeded($context);
    }

    #[Override]
    public function onIdle(Context $context): void
    {
        $this->interruptExecutionIfNeeded($context);
    }

    private function interruptExecutionIfNeeded(Context $context): void
    {
        if (false === $context->isExecutionInterrupted() && $this->interrupt) {
            $this->logger->debug('[SignalExtension] Interrupt');

            $context->interruptExecution();
            $this->interrupt = false;
        }
    }

    public function handleSignal(int $signal): void
    {
        $this->logger->debug(sprintf('[SignalExtension] Caught signal: %s', $signal));

        switch ($signal) {
            case SIGTERM:
            case SIGQUIT:
            case SIGINT:
                $this->logger->debug('[SignalExtension] Interrupt');

                $this->interrupt = true;

                break;
            default:
                break;
        }
    }
}
