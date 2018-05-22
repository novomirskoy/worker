<?php

namespace Endin\Daemon\Extension;

use DateTime;
use Endin\Daemon\Context;
use Endin\Daemon\EmptyExtensionTrait;
use Endin\Daemon\ExtensionInterface;

class LimitTimeExtension implements ExtensionInterface
{
    use EmptyExtensionTrait;

    /**
     * @var DateTime
     */
    private $timeLimit;

    /**
     * LimitTimeExtension constructor.
     * @param DateTime $timeLimit
     */
    public function __construct(DateTime $timeLimit)
    {
        $this->timeLimit = $timeLimit;
    }

    /**
     * @inheritdoc
     */
    public function onBeforeRunning(Context $context)
    {
        $this->checkTime($context);
    }

    /**
     * @inheritdoc
     */
    public function onAfterRunning(Context $context)
    {
        $this->checkTime($context);
    }

    /**
     * @inheritdoc
     */
    public function onIdle(Context $context)
    {
        $this->checkTime($context);
    }

    /**
     * @param Context $context
     *
     * @return void
     */
    private function checkTime(Context $context)
    {
        $now = new DateTime();

        if ($now >= $this->timeLimit) {
            $context->getLogger()->debug(sprintf(
                '[LimitTimeExtension] Execution interrupted as limit time has passed.' .
                ' now: "%s", time-limit: "%s"',
                $now->format(DATE_ATOM),
                $this->timeLimit->format(DATE_ATOM)
            ));

            $context->setExecutionInterrupted(true);
        }
    }
}
