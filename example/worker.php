<?php

use Novomirskoy\Worker\ChainExtension;
use Novomirskoy\Worker\Worker;
use Novomirskoy\Worker\Extension\LimitTickExtension;
use Novomirskoy\Worker\Extension\LimitTimeExtension;
use Novomirskoy\Worker\Extension\SignalExtension;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$logger = new class implements LoggerInterface {
    public function emergency(\Stringable|string $message, array $context = [])
    {
    }

    public function alert(\Stringable|string $message, array $context = [])
    {
    }

    public function critical(\Stringable|string $message, array $context = [])
    {
    }

    public function error(\Stringable|string $message, array $context = [])
    {
    }

    public function warning(\Stringable|string $message, array $context = [])
    {
    }

    public function notice(\Stringable|string $message, array $context = [])
    {
    }

    public function info(\Stringable|string $message, array $context = [])
    {
    }

    public function debug(\Stringable|string $message, array $context = [])
    {
        echo $message, PHP_EOL;
    }

    public function log($level, \Stringable|string $message, array $context = [])
    {
    }

};

$extension = new ChainExtension([
    new SignalExtension(),
    new LimitTimeExtension(new DateTimeImmutable('+10 sec')),
    new LimitTickExtension(1000),
]);

$worker = new Worker($logger, $extension, 1000);
$worker->run();
