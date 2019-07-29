<?php

namespace Novomirskoy\Worker\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;

/**
 * Interface RegistryInterface
 *
 * @package Novomirskoy\Worker\Doctrine
 */
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
