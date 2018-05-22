<?php

namespace Endin\Daemon\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;

interface RegistryInterface
{
    /**
     * @return ObjectManager[]
     */
    public function getManagers();

    /**
     * @return Connection[]
     */
    public function getConnections();
}