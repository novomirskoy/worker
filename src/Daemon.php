<?php

namespace Endin\Daemon;

use Exception;
use Psr\Log\LoggerInterface;

class Daemon
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
     * @var int in milliseconds
     */
    private $idleTimeout;

    public function __construct(
        LoggerInterface $logger,
        ExtensionInterface $extension,
        $idleTimeout = 0
    ) {
        $this->logger = $logger;
        $this->extension = $extension;
        $this->idleTimeout = $idleTimeout;
    }

    public function run()
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

    /**
     * @return int
     */
    public function getIdleTimeout()
    {
        return $this->idleTimeout;
    }

    /**
     * @param int $idleTimeout
     */
    public function setIdleTimeout($idleTimeout)
    {
        $this->idleTimeout = (int)$idleTimeout;
    }
}
