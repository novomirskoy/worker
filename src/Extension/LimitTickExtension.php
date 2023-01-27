<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Extension;

use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;

final class LimitTickExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    private $tickLimit;
    private $tickCount = 0;

    public function __construct(
        int $tickLimit,
        int $tickCount = 0
    ) {
        $this->tickCount = $tickCount;
        $this->tickLimit = $tickLimit;
    }

    public function onBeforeRunning(Context $context): void
    {
        $this->checkLimit($context);
    }

    public function onAfterRunning(Context $context): void
    {
        ++$this->tickCount;

        $this->checkLimit($context);
    }

    private function checkLimit(Context $context): void
    {
        if ($this->tickCount >= $this->tickLimit) {
            $context->getLogger()->debug(sprintf(
                '[LimitTickExtension] Превышен лимит допустимых операций. Ограничение: "%s"',
                $this->tickLimit
            ));

            $context->setExecutionInterrupted(true);
        }
    }
}
