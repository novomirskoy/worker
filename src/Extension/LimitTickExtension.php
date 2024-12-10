<?php

declare(strict_types=1);

namespace Novomirskoy\Worker\Extension;

use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;
use Override;

final class LimitTickExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    public function __construct(
        private readonly int $tickLimit,
        private int $tickCount = 0,
    ) {}

    #[Override]
    public function onBeforeRunning(Context $context): void
    {
        $this->checkLimit($context);
    }

    #[Override]
    public function onAfterRunning(Context $context): void
    {
        ++$this->tickCount;

        $this->checkLimit($context);
    }

    private function checkLimit(Context $context): void
    {
        if ($this->tickCount >= $this->tickLimit) {
            $context->logger->debug(sprintf(
                '[LimitTickExtension] Превышен лимит допустимых операций. Ограничение: "%s"',
                $this->tickLimit,
            ));

            $context->interruptExecution();
        }
    }
}
