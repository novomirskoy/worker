<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Extension;

use LogicException;
use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;
use Psr\Log\LoggerInterface;

final class SignalExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var bool
     */
    private $interrupt = false;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function onStart(Context $context): void
    {
        if (false === extension_loaded('pcntl')) {
            throw new LogicException('The pcntl extension is required in order to catch signals');
        }

        if (function_exists('pcntl_async_signals')) {
            pcntl_async_signals(true);
        }

        pcntl_signal(SIGTERM, [$this, 'handleSignal']);
        pcntl_signal(SIGQUIT, [$this, 'handleSignal']);
        pcntl_signal(SIGINT, [$this, 'handleSignal']);

        $this->interrupt = false;

        $this->logger = $context->getLogger();
    }

    public function onBeforeRunning(Context $context): void
    {
        $this->dispatchSignal();

        $this->interruptExecutionIfNeeded($context);
    }

    public function onAfterRunning(Context $context): void
    {
        $this->dispatchSignal();

        $this->interruptExecutionIfNeeded($context);
    }

    public function onIdle(Context $context): void
    {
        $this->dispatchSignal();

        $this->interruptExecutionIfNeeded($context);
    }

    public function interruptExecutionIfNeeded(Context $context): void
    {
        if (false === $context->isExecutionInterrupted() && $this->interrupt) {
            $this->logger->debug('[SignalExtension] Interrupt');

            $context->setExecutionInterrupted($this->interrupt);
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

    /**
     * @return void
     */
    private function dispatchSignal(): void
    {
        if (false === function_exists('pcntl_async_signals')) {
            pcntl_signal_dispatch();
        }
    }
}
