<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Extension;

use DateInterval;
use DateTimeImmutable;
use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;
use Override;

final readonly class LimitDurationExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    private DateTimeImmutable $timeLimit;

    public function __construct(DateInterval|string $duration)
    {
        $this->timeLimit = (new DateTimeImmutable())->add(
            is_string($duration) ? new DateInterval($duration) : $duration
        );
    }

    #[Override]
    public function onBeforeRunning(Context $context): void
    {
        $this->checkDuration($context);
    }

    #[Override]
    public function onAfterRunning(Context $context): void
    {
        $this->checkDuration($context);
    }

    #[Override]
    public function onIdle(Context $context): void
    {
        $this->checkDuration($context);
    }

    private function checkDuration(Context $context): void
    {
        $now = new DateTimeImmutable();

        if ($now >= $this->timeLimit) {
            $context->logger->debug('[LimitDurationExtension] Execution interrupted as runtime duration limit has passed.');
            $context->interruptExecution();
        }
    }
}
