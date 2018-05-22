<?php

namespace Endin\Daemon\Doctrine;

class DummyRegistry implements RegistryInterface
{
    /**
     * @inheritdoc
     */
    public function getManagers()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getConnections()
    {
        return [];
    }
}
