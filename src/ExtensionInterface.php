<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

interface ExtensionInterface
{
    public function onStart(Context $context): void;

    public function onBeforeRunning(Context $context): void;

    public function onRunning(Context $context): void;

    public function onAfterRunning(Context $context): void;

    /**
     * Called each time at the end of the cycle if nothing was done.
     */
    public function onIdle(Context $context): void;

    public function onInterrupted(Context $context): void;
}
