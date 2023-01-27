<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

final class ChainExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var ExtensionInterface[]
     */
    private $extensions;

    public function __construct(array $extensions = [])
    {
        $this->extensions = $extensions;
    }

    public function onStart(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onStart($context);
        }
    }

    public function onBeforeRunning(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onBeforeRunning($context);
        }
    }

    public function onRunning(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onRunning($context);
        }
    }

    public function onAfterRunning(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onAfterRunning($context);
        }
    }

    public function onIdle(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onIdle($context);
        }
    }

    public function onInterrupted(Context $context): void
    {
        foreach ($this->extensions as $extension) {
            $extension->onInterrupted($context);
        }
    }
}
