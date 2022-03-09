<?php

use Novomirskoy\Worker\ChainExtension;
use Novomirskoy\Worker\Extension\LimitTickExtension;
use Novomirskoy\Worker\Extension\LimitTimeExtension;
use Novomirskoy\Worker\Extension\SignalExtension;
use Novomirskoy\Worker\Worker;
use Psr\Log\NullLogger;

require_once __DIR__ . '/../vendor/autoload.php';

$logger = new class () extends NullLogger {
    public function log($level, Stringable|string $message, array $context = []): void
    {
        echo $message, PHP_EOL;
    }
};

$extension = new ChainExtension([
    new SignalExtension(),
    new LimitTimeExtension(new DateTimeImmutable('+10 sec')),
    new LimitTickExtension(1000),
]);

$worker = new Worker($logger, $extension, 1000);
$worker->run();
