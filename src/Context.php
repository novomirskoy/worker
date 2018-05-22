<?php

namespace Endin\Daemon;


use Psr\Log\LoggerInterface;

class Context
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $executionInterrupted;

    /**
     * Context constructor.
     */
    public function __construct()
    {
        $this->executionInterrupted = false;
    }

    /**
     * @return bool
     */
    public function isExecutionInterrupted()
    {
        return $this->executionInterrupted;
    }

    /**
     * @param bool $executionInterrupted
     */
    public function setExecutionInterrupted($executionInterrupted)
    {
        if (false === $executionInterrupted && $this->executionInterrupted) {
            throw new \RuntimeException('The execution once interrupted could no be roll backed');
        }

        $this->executionInterrupted = $executionInterrupted;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }
}