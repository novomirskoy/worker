<?php

namespace Novomirskoy\Worker;

use Exception;
use Psr\Log\LoggerInterface;

/**
 * Class Worker
 *
 * @package Novomirskoy\Worker
 */
class Worker
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

    /**
     * Worker constructor.
     *
     * @param LoggerInterface $logger
     * @param ExtensionInterface $extension
     * @param int $idleTimeout
     */
    public function __construct(
        LoggerInterface $logger,
        ExtensionInterface $extension,
        $idleTimeout = 0
    ) {
        $this->logger = $logger;
        $this->extension = $extension;
        $this->setIdleTimeout($idleTimeout);
    }

    /**
     * @return void
     *
     * @throws Exception
     */
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
                $this->extension->onError($context, $e);

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
