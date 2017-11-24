<?php

namespace Cron\Cron;

use Cake\Log\Log;
use Cron\Cron\CronTaskInterface;

/**
 * Class Task
 *
 * @package Cron\Cron
 */
abstract class CronTask
{
    protected $_log = [];

    /**
     * @return array|TaskResult
     */
    abstract function execute();

    /**
     * Convenience log method
     *
     * @param $message
     * @param string $level
     */
    public function log($message, $level = 'info')
    {
        $className = get_class($this);
        $taskName = substr($className, strrpos($className, '\\') + 1);
        $this->_log[] = $message = sprintf("[%s] %s", $taskName, $message);

        Log::write($level, $message);
    }
}