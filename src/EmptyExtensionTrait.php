<?php

namespace Novomirskoy\Worker;

/**
 * Trait EmptyExtensionTrait
 *
 * @package Novomirskoy\Worker
 */
trait EmptyExtensionTrait
{
    /**
     * @inheritDoc
     */
    public function onStart(Context $context)
    {
    }

    /**
     * @inheritDoc
     */
    public function onBeforeRunning(Context $context)
    {
    }

    /**
     * @inheritDoc
     */
    public function onRunning(Context $context)
    {
    }

    /**
     * @inheritDoc
     */
    public function onAfterRunning(Context $context)
    {
    }

    /**
     * @inheritDoc
     */
    public function onIdle(Context $context)
    {
    }

    /**
     * @inheritDoc
     */
    public function onInterrupted(Context $context)
    {
    }
}
