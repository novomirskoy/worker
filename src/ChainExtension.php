<?php

namespace Novomirskoy\Worker;

class ChainExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var ExtensionInterface[]
     */
    private $extensions;

    /**
     * ChainExtension constructor.
     * @param array $extensions
     */
    public function __construct(array $extensions = [])
    {
        $this->extensions = $extensions;
    }

    /**
     * @inheritdoc
     */
    public function onStart(Context $context)
    {
        foreach ($this->extensions as $extension) {
            $extension->onStart($context);
        }
    }

    /**
     * @inheritdoc
     */
    public function onBeforeRunning(Context $context)
    {
        foreach ($this->extensions as $extension) {
            $extension->onBeforeRunning($context);
        }
    }

    /**
     * @inheritdoc
     */
    public function onRunning(Context $context)
    {
        foreach ($this->extensions as $extension) {
            $extension->onRunning($context);
        }
    }

    /**
     * @inheritdoc
     */
    public function onAfterRunning(Context $context)
    {
        foreach ($this->extensions as $extension) {
            $extension->onAfterRunning($context);
        }
    }

    /**
     * @inheritdoc
     */
    public function onIdle(Context $context)
    {
        foreach ($this->extensions as $extension) {
            $extension->onIdle($context);
        }
    }

    /**
     * @inheritdoc
     */
    public function onInterrupted(Context $context)
    {
        foreach ($this->extensions as $extension) {
            $extension->onInterrupted($context);
        }
    }
}
