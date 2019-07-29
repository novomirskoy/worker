<?php

namespace Novomirskoy\Worker\Extension;

use Novomirskoy\Worker\Context;
use Novomirskoy\Worker\EmptyExtensionTrait;
use Novomirskoy\Worker\ExtensionInterface;

/**
 * Class LimitTickExtension
 *
 * @package Novomirskoy\Worker\Extension
 */
class LimitTickExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var int
     */
    private $tickLimit;

    /**
     * @var int
     */
    private $tickCount;

    /**
     * LimitTickExtension constructor.
     * @param int $tickLimit
     */
    public function __construct($tickLimit)
    {
        if (!is_int($tickLimit)) {
            throw new \InvalidArgumentException();
        }

        $this->tickLimit = $tickLimit;
        $this->tickCount = 0;
    }

    /**
     * @inheritdoc
     */
    public function onBeforeRunning(Context $context)
    {
        $this->checkLimit($context);
    }

    /**
     * @inheritdoc
     */
    public function onAfterRunning(Context $context)
    {
        ++$this->tickCount;

        $this->checkLimit($context);
    }

    /**
     * @param Context $context
     *
     * @return void
     */
    private function checkLimit(Context $context)
    {
        if ($this->tickCount >= $this->tickLimit) {
            $context->getLogger()->debug(sprintf(
                '[LimitTickExtension] Превышен лимит допустих операций. Ограничение: "%s"',
                $this->tickLimit
            ));

            $context->setExecutionInterrupted(true);
        }
    }
}
