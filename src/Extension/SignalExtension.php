<?php

namespace Endin\Daemon\Extension;

use Endin\Daemon\Context;
use Endin\Daemon\EmptyExtensionTrait;
use Endin\Daemon\ExtensionInterface;
use Psr\Log\LoggerInterface;

class SignalExtension implements ExtensionInterface
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

    /**
     * @inheritdoc
     */
    public function onStart(Context $context)
    {
        if (false === extension_loaded('pcntl')) {
            throw new \LogicException('The pcntl extension is required in order to catch signals');
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

    /**
     * @inheritdoc
     */
    public function onBeforeRunning(Context $context)
    {
        $this->dispatchSignal();

        $this->interruptExecutionIfNeeded($context);
    }

    /**
     * @inheritdoc
     */
    public function onAfterRunning(Context $context)
    {
        $this->dispatchSignal();

        $this->interruptExecutionIfNeeded($context);
    }

    /**
     * @inheritdoc
     */
    public function onIdle(Context $context)
    {
        $this->dispatchSignal();

        $this->interruptExecutionIfNeeded($context);
    }

    /**
     * @param Context $context
     *
     * @return void
     */
    public function interruptExecutionIfNeeded(Context $context)
    {
        if (false === $context->isExecutionInterrupted() && $this->interrupt) {
            $this->logger->debug('[SignalExtension] Interrupt');

            $context->setExecutionInterrupted($this->interrupt);
            $this->interrupt = false;
        }
    }

    /**
     * @param int $signal
     *
     * @return void
     */
    public function handleSignal($signal)
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
    private function dispatchSignal()
    {
        if (false === function_exists('pcntl_async_signals')) {
            pcntl_signal_dispatch();
        }
    }
}
