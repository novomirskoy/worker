<?php

namespace Endin\Daemon\Extension;

use Endin\Daemon\Context;
use Endin\Daemon\Doctrine\RegistryInterface;
use Endin\Daemon\EmptyExtensionTrait;
use Endin\Daemon\ExtensionInterface;

class DoctrinePingConnectionExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * DoctrinePingConnectionExtension constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritdoc
     */
    public function onBeforeRunning(Context $context)
    {
        foreach ($this->registry->getConnections() as $connection) {
            if (!$connection->isConnected()) {
                continue;
            }

            if ($connection->ping()) {
                continue;
            }

            $context->getLogger()->debug(
                '[DoctrinePingConnectionExtension] Connection is not active trying to reconnect.'
            );

            $connection->close();
            $connection->connect();

            $context->getLogger()->debug(
                '[DoctrinePingConnectionExtension] Connection is active now.'
            );
        }
    }
}
