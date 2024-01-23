<?php

namespace Novomirskoy\Worker;

use Exception;

interface ExtensionInterface
{
    /**
     * @param Context $context
     *
     * @return void
     */
    public function onStart(Context $context);

    /**
     * @param Context $context
     *
     * @return void
     */
    public function onBeforeRunning(Context $context);

    /**
     * @param Context $context
     *
     * @return void
     */
    public function onRunning(Context $context);

    /**
     * @param Context $context
     *
     * @return void
     */
    public function onAfterRunning(Context $context);

    /**
     * Called each time at the end of the cycle if nothing was done.
     *
     * @param Context $context
     *
     * @return void
     */
    public function onIdle(Context $context);

    /**
     * @param Context $context
     *
     * @return void
     */
    public function onInterrupted(Context $context);

    /**
     * @param Context $context
     * @param Exception|null $exception
     *
     * @return void
     */
    public function onError(Context $context, Exception $exception = null);
}
