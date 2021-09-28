<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

use Exception;
use Psr\Log\LoggerInterface;

final class Worker
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ExtensionInterface
     */
    private ExtensionInterface $extension;

    /**
     * @var int in milliseconds
     */
    private int $idleTimeout;

    public function __construct(
        LoggerInterface $logger,
        ExtensionInterface $extension,
        int $idleTimeout = 0
    ) {
        $this->logger = $logger;
        $this->extension = $extension;
        $this->setIdleTimeout($idleTimeout);
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $context = new Context();
        $context->setLogger($this->logger);

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

                if ($context->isExecutionInterrupted()) {
                    throw new InterruptedException();
                }
            } catch (InterruptedException $e) {
                $context->setExecutionInterrupted(true);
                $this->extension->onInterrupted($context);

                return;
            } catch (Exception $e) {
                $context->setExecutionInterrupted(true);

                throw $e;
            }
        }
    }

    public function getIdleTimeout(): int
    {
        return $this->idleTimeout;
    }

    public function setIdleTimeout(int $idleTimeout): void
    {
        $this->idleTimeout = $idleTimeout;
    }
}
