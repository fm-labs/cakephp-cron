<?php
declare(strict_types=1);

namespace Cron\Cron\Task;

use Cake\Log\Log;
use Cron\Cron\ICronTask;

/**
 * Class Task
 *
 * @package Cron\Cron
 */
abstract class BaseCronTask implements ICronTask
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
        $message = sprintf("[%s] %s", $taskName, $message);

        $this->_log[] = [$level, $message];

        Log::write($level, $message, ['cron']);
    }
}
