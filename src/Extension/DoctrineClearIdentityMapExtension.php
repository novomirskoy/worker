<?php

namespace Endin\Daemon\Extension;

use Endin\Daemon\Context;
use Endin\Daemon\Doctrine\RegistryInterface;
use Endin\Daemon\EmptyExtensionTrait;
use Endin\Daemon\ExtensionInterface;

class DoctrineClearIdentityMapExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * DoctrineClearIdentityMapExtension constructor.
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
        foreach ($this->registry->getManagers() as $name => $manager) {
            $context->getLogger()->debug(sprintf(
                '[DoctrineClearIdentityMapExtension] Clear identity map for manager "%s"',
                $name
            ));

            $manager->clear();
        }
    }
}
