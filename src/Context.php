<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

use Psr\Log\LoggerInterface;

final class Context
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var bool
     */
    private $executionInterrupted = false;

    public function __construct(
        LoggerInterface $logger,
        bool $executionInterrupted = false
    ) {
        $this->executionInterrupted = $executionInterrupted;
        $this->logger = $logger;
    }

    public function isExecutionInterrupted(): bool
    {
        return $this->executionInterrupted;
    }

    public function interruptExecution(): void
    {
        $this->executionInterrupted = true;
    }

    public function logger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @deprecated
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
}
