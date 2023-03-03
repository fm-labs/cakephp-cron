<?php
declare(strict_types=1);

namespace Cron\Cron;

use Cake\Log\Log;

/**
 * Class Task
 *
 * @package Cron\Cron
 */
abstract class BaseCronTask implements ICronTask
{
    protected array $_log = [];

    /**
     * @return void|CronTaskResult
     */
    abstract function execute();

    /**
     * Convenience log method
     *
     * @param $message
     * @param string $level
     */
    public function log($message, string $level = 'info')
    {
        $className = static::class;
        $taskName = substr($className, strrpos($className, '\\') + 1);
        $message = sprintf("[%s] %s", $taskName, $message);

        $this->_log[] = [$level, $message];

        Log::write($level, $message, ['cron']);
    }
}
