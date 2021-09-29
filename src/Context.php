<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

use Psr\Log\LoggerInterface;

final class Context
{
    public function __construct(
        private LoggerInterface $logger,
        private bool $executionInterrupted = false
    ) {
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
