<?php

use Novomirskoy\Worker\ChainExtension;
use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\Exception\ExceptionInterface;
use Novomirskoy\Worker\Extension\LimitTickExtension;
use Novomirskoy\Worker\Extension\LimitTimeExtension;
use Novomirskoy\Worker\Extension\SignalExtension;
use Novomirskoy\Worker\Worker;
use Psr\Log\NullLogger;

require_once __DIR__ . '/../vendor/autoload.php';

$logger = new class extends NullLogger {
    public function log($level, Stringable|string $message, array $context = []): void
    {
        echo $message, PHP_EOL;
    }
};

$tickExtension = new class implements ExceptionInterface {
    use EmptyExtensionTrait;

    public function __construct(
        private int $tick = 0,
    ) {}

    public function onRunning(Context $context): void
    {
        $context->logger->info("[TickExtension] Tick number: $this->tick");
        ++$this->tick;
    }
};

$extension = new ChainExtension([
    new SignalExtension(),
    new LimitTimeExtension(new DateTimeImmutable('+10 sec')),
    new LimitTickExtension(1000),
    $tickExtension,
]);

$worker = new Worker($logger, $extension, 1000);
$worker->run();
