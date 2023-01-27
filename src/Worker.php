<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

use Novomirskoy\Worker\Exception\InterruptedException;
use Psr\Log\LoggerInterface;
use Throwable;

final class Worker
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ExtensionInterface
     */
    private $extension;

    /**
     * @var int
     */
    private $idleTimeout;

    public function __construct(
        LoggerInterface $logger,
        ExtensionInterface $extension,
        int $idleTimeout = 0
    ) {
        $this->idleTimeout = $idleTimeout;
        $this->extension = $extension;
        $this->logger = $logger;
    }

    /**
     * @throws InterruptedException
     * @throws Throwable
     */
    public function run(): void
    {
        $context = new Context($this->logger);

        $this->extension->onStart($context);

        while (true) {
            try {
                if ($context->isExecutionInterrupted()) {
                    throw new InterruptedException();
                }

                $this->extension->onBeforeRunning($context);
                $this->extension->onRunning($context);
                $this->extension->onAfterRunning($context);

                usleep($this->idleTimeout * 1000);
                $this->extension->onIdle($context);
            } catch (InterruptedException $e) {
                $context->interruptExecution();
                $this->extension->onInterrupted($context);

                return;
            } catch (Throwable $e) {
                $context->interruptExecution();

                throw $e;
            }
        }
    }
}
