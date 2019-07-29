<?php

namespace Novomirskoy\Worker\Extension;

use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\Doctrine\RegistryInterface;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;

/**
 * Class DoctrineClearIdentityMapExtension
 *
 * @package Novomirskoy\Worker\Extension
 */
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
