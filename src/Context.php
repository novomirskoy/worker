<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

use Psr\Log\LoggerInterface;

final class Context
{
    public function __construct(
        public readonly LoggerInterface $logger,
        private bool $executionInterrupted = false,
    ) {}

    public function isExecutionInterrupted(): bool
    {
        return $this->executionInterrupted;
    }

    public function interruptExecution(): void
    {
        $this->executionInterrupted = true;
    }
}
