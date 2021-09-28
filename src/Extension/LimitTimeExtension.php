<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Extension;

use DateTimeImmutable;
use Exception;
use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;

final class LimitTimeExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    private DateTimeImmutable $timeLimit;

    public function __construct(DateTimeImmutable $timeLimit)
    {
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

    /**
     * @throws Exception
     */
    private function checkTime(Context $context): void
    {
        $now = new DateTimeImmutable();

        if ($now >= $this->timeLimit) {
            $context->getLogger()->debug(sprintf(
                '[LimitTimeExtension] Execution interrupted as limit time has passed.' .
                ' now: "%s", time-limit: "%s"',
                $now->format(DATE_ATOM),
                $this->timeLimit->format(DATE_ATOM)
            ));

            $context->setExecutionInterrupted(true);
        }
    }
}
