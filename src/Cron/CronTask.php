<?php

namespace Cron\Cron;

use Cake\Log\Log;
use Cron\Cron\CronTaskInterface;

/**
 * Class CronTask
 * @package Cron\Cron
 */
abstract class CronTask implements CronTaskInterface
{
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
        $message = sprintf("[%s] %s", $taskName, $message);
        $context = ['cron'];

        Log::write($level, $message, $context);
    }
}