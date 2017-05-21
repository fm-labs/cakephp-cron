<?php

namespace Cron\Cron\Task;


use Cake\Log\Log;
use Cron\Cron\CronTaskInterface;

abstract class CronTask implements CronTaskInterface
{
    public function log($message, $level = 'info')
    {
        $className = get_class($this);
        $taskName = substr($className, strrpos($className, '\\') + 1);
        //$time = (new \DateTime())->format('Y-m-d H:i:s');
        $message = sprintf("[%s] %s", $taskName, $message);
        $context = ['cron'];

        Log::write($level, $message, $context);
    }
}