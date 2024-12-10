<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

use Override;

final class ChainExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var ExtensionInterface[]
     */
    private array $extensions;

    public function __construct(array $extensions = [])
    {
        $this->extensions = $extensions;
    }

    #[Override]
    public function onStart(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onStart($context);
        }
    }

    #[Override]
    public function onBeforeRunning(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onBeforeRunning($context);
        }
    }

    #[Override]
    public function onRunning(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onRunning($context);
        }
    }

    #[Override]
    public function onAfterRunning(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onAfterRunning($context);
        }
    }

    #[Override]
    public function onIdle(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onIdle($context);
        }
    }

    #[Override]
    public function onInterrupted(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onInterrupted($context);
        }
    }
}
