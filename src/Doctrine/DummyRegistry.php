<?php

namespace Novomirskoy\Worker\Doctrine;

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
