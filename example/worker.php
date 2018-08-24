<?php

use Novomirskoy\Worker\ChainExtension;
use Novomirskoy\Worker\Worker;
use Novomirskoy\Worker\Doctrine\DummyRegistry;
use Novomirskoy\Worker\Extension\DoctrineClearIdentityMapExtension;
use Novomirskoy\Worker\Extension\DoctrinePingConnectionExtension;
use Novomirskoy\Worker\Extension\LimitTickExtension;
use Novomirskoy\Worker\Extension\LimitTimeExtension;
use Novomirskoy\Worker\Extension\SignalExtension;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/../vendor/autoload.php';

$logger = new Logger('daemon-logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/worker.log', Logger::DEBUG));

$doctrineRegistry = new DummyRegistry();

$extension = new ChainExtension([
    new SignalExtension(),
    new LimitTimeExtension(new DateTime('+10 sec')),
    new LimitTickExtension(1000),
    new DoctrineClearIdentityMapExtension($doctrineRegistry),
    new DoctrinePingConnectionExtension($doctrineRegistry),
]);

$worker = new Worker($logger, $extension, 1000);
$worker->run();
