<?php

namespace Novomirskoy\Worker;

trait EmptyExtensionTrait
{
    /**
     * @param Context $context
     */
    public function onStart(Context $context)
    {

    }

    /**
     * @param Context $context
     */
    public function onBeforeRunning(Context $context)
    {

    }

    /**
     * @param Context $context
     */
    public function onRunning(Context $context)
    {

    }

    /**
     * @param Context $context
     */
    public function onAfterRunning(Context $context)
    {

    }

    /**
     * @param Context $context
     */
    public function onIdle(Context $context)
    {

    }

    /**
     * @param Context $context
     */
    public function onInterrupted(Context $context)
    {

    }
}
