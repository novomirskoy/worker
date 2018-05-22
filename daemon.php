<?php

use Endin\Daemon\ChainExtension;
use Endin\Daemon\Daemon;
use Endin\Daemon\Doctrine\DummyRegistry;
use Endin\Daemon\Extension\DoctrineClearIdentityMapExtension;
use Endin\Daemon\Extension\DoctrinePingConnectionExtension;
use Endin\Daemon\Extension\LimitTickExtension;
use Endin\Daemon\Extension\LimitTimeExtension;
use Endin\Daemon\Extension\SignalExtension;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

require_once __DIR__ . '/vendor/autoload.php';

$logger = new Logger('daemon-logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/data/daemon.log', Logger::DEBUG));

$doctrineRegistry = new DummyRegistry();

$extension = new ChainExtension([
    new SignalExtension(),
    new LimitTimeExtension(new DateTime('+10 sec')),
    new LimitTickExtension(1000),
    new DoctrineClearIdentityMapExtension($doctrineRegistry),
    new DoctrinePingConnectionExtension($doctrineRegistry),
]);

$daemon = new Daemon($logger, $extension, 1);
$daemon->run();