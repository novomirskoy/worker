<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

use Psr\Log\LoggerInterface;
use RuntimeException;

final class Context
{
    /**
     * @var
     */
    private $logger;

    /**
     * @var false
     */
    private $executionInterrupted;

    public function __construct()
    {
        $this->executionInterrupted = false;
    }

    public function isExecutionInterrupted(): bool
    {
        return $this->executionInterrupted;
    }

    public function setExecutionInterrupted(bool $executionInterrupted): void
    {
        if (false === $executionInterrupted && $this->executionInterrupted) {
            throw new RuntimeException('The execution once interrupted could no be roll backed');
        }

        $this->executionInterrupted = $executionInterrupted;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
