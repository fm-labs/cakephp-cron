<?php
declare(strict_types=1);

namespace Cron\Cron;

use Cake\Log\Log;

/**
 * Class Task
 *
 * @package Cron\Cron
 */
abstract class CronTask
{
    protected $_log = [];

    /**
     * @return array|\Cron\Cron\CronTaskResult
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
        $className = static::class;
        $taskName = substr($className, strrpos($className, '\\') + 1);
        $this->_log[] = $message = sprintf("[%s] %s", $taskName, $message);

        Log::write($level, $message, ['cron']);
    }
}
