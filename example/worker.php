<?php

use Novomirskoy\Worker\ChainExtension;
use Novomirskoy\Worker\Worker;
use Novomirskoy\Worker\Extension\LimitTickExtension;
use Novomirskoy\Worker\Extension\LimitTimeExtension;
use Novomirskoy\Worker\Extension\SignalExtension;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/../vendor/autoload.php';

$logger = new class implements LoggerInterface {
    public function emergency($message, array $context = [])
    {
    }

    public function alert($message, array $context = [])
    {
    }

    public function critical($message, array $context = [])
    {
    }

    public function error($message, array $context = [])
    {
    }

    public function warning($message, array $context = [])
    {
    }

    public function notice($message, array $context = [])
    {
    }

    public function info($message, array $context = [])
    {
    }

    public function debug($message, array $context = [])
    {
        echo $message, PHP_EOL;
    }

    public function log($level, $message, array $context = [])
    {
    }

};

$extension = new ChainExtension([
    new SignalExtension(),
    new LimitTimeExtension(new DateTimeImmutable('+3 sec')),
    new LimitTickExtension(300),
]);

$worker = new Worker($logger, $extension, 300);
$worker->run();
