<?php

declare(strict_types=1);

namespace Novomirskoy\Worker;

trait EmptyExtensionTrait
{
    public function onStart(Context $context): void {}

    public function onBeforeRunning(Context $context): void {}

    public function onRunning(Context $context): void {}

    public function onAfterRunning(Context $context): void {}

    public function onIdle(Context $context): void {}

    public function onInterrupted(Context $context): void {}
}
