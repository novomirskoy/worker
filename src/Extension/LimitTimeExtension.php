<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Extension;

use DateTimeImmutable;
use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;

final class LimitTimeExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var DateTimeImmutable
     */
    private $timeLimit;

    public function __construct(
        DateTimeImmutable $timeLimit
    ) {
        $this->timeLimit = $timeLimit;
    }

    public function onBeforeRunning(Context $context): void
    {
        $this->checkTime($context);
    }

    public function onAfterRunning(Context $context): void
    {
        $this->checkTime($context);
    }

    public function onIdle(Context $context): void
    {
        $this->checkTime($context);
    }

    private function checkTime(Context $context): void
    {
        $now = new DateTimeImmutable();

        if ($now >= $this->timeLimit) {
            $context->logger()->debug(sprintf(
                '[LimitTimeExtension] Execution interrupted as limit time has passed.' .
                ' now: "%s", time-limit: "%s"',
                $now->format(DATE_ATOM),
                $this->timeLimit->format(DATE_ATOM)
            ));

            $context->interruptExecution();
        }
    }
}
